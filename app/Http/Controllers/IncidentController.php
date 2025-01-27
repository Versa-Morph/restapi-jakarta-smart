<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Instance;
use App\Models\InstanceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $page_title = 'Incidents';

        $query = Incident::where('status', '!=', 'requested')->orderBy('created_at', 'DESC');

        if ($user->role !== 'admin') {
            $query->where('responder_id', $user->id);
        }

        $incidents = $query->get();

        return view('incidents.index', compact('incidents', 'page_title'));
    }

    public function complete($id, Request $request)
    {
        $incident = Incident::find($id);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        $user = Auth::user();

        if ($incident->responder_id !== $user->id) {
            return response()->json(['message' => 'Instansi terkait yang hanya dapat menyelesaikan!'], 403);
        }

        $incident->status = 'completed';
        $incident->save();

        return response()->json(['message' => 'Incident completed successfully']);
    }

    public function accept($id, Request $request)
    {
        $incident = Incident::find($id);

        if (!$incident) {
            return response()->json(['message' => 'Incident not found'], 404);
        }

        $user = Auth::user();

        if ($incident->responder_id !== $user->id) {
            return response()->json(['message' => 'Instansi terkait yang hanya dapat menerima!'], 403);
        }

        $incident->status = 'processed';
        $incident->save();

        return response()->json(['message' => 'Incident accepted successfully']);
    }

    public function queue()
    {
        $user = Auth::user();
        $page_title = 'Queue';

        $query = Incident::where('status', 'requested')->orderBy('created_at', 'DESC');

        if ($user->role !== 'admin') {
            $query->where('responder_id', $user->id);
        }

        $incidents = $query->get();

        return view('queue.index', compact('incidents', 'page_title'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $incident)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incident $incident)
    {
        //
    }

    // =================================================================================
    // API Controller
    // =================================================================================
    public function apiGetMyIncident()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'User not authenticated',
                    'data' => (object)[]
                ], 404);
            }

            $incidents = Incident::orderBy('created_at', 'DESC')
                                ->where('caller_id', $user->id)
                                ->get();

            if ($incidents->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Incidents not found',
                    'data' => (object)[]
                ], 404);
            }

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Incidents retrieved successfully',
                'data' => $incidents
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'An error occurred',
                'data' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    public function apiGetMyIncidentByStatus()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'User not authenticated',
                    'data' => (object)[]
                ], 404);
            }

            $statuses = ['requested', 'processed', 'completed'];
            $data = [];

            foreach ($statuses as $status) {
                $incidents = Incident::where('status', $status)
                                    ->orderBy('created_at', 'DESC')
                                    ->where('caller_id', $user->id)
                                    ->get();

                if (!$incidents->isEmpty()) {
                    $data[$status] = [
                        'count' => $incidents->count(),
                        'incidents' => $incidents
                    ];
                } else {
                    $data[$status] = [
                        'count' => 0,
                        'incidents' => []
                    ];
                }
            }

            if (empty(array_filter($data, fn($value) => $value['count'] > 0))) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'No incidents found',
                    'data' => (object)[]
                ], 404);
            }

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Incidents by status retrieved successfully',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'An error occurred',
                'data' => ['error' => $e->getMessage()]
            ], 500);
        }
    }

    public function apiStoreIncident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pluscode'    => 'required',
            'longitude'   => 'nullable|numeric',
            'latitude'    => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'User not authenticated',
                'data' => (object)[]
            ], 401);
        }

        DB::beginTransaction();
        try {
            // Generate incident number
            $incidentNumber = $this->generateIncidentNumber();

             // Handle image upload
             $imagePath = null;
             if ($request->hasFile('image')) {
                 $file = $request->file('image');
                 $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                 $file->move(public_path('incident_images'), $filename);
                 $imagePath = 'incident_images/' . $filename;
            }

            $instanceResponder = InstanceDetail::where('pluscode', $request->pluscode)->first();

            $incident = Incident::create([
                'incident_number'    => $incidentNumber,
                'caller_id'          => $user->id,
                'responder_id'       => $instanceResponder->users[0]->id,
                'description'        => $request->description,
                'longitude'          => $request->longitude,
                'latitude'           => $request->latitude,
                'image'              => $imagePath,
                'request_datetime'   => now(),
                'process_datetime'   => null,
                'complete_datetime'  => null,
                'status'             => 'requested'
            ]);
            DB::commit();

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Incident created successfully',
                'data' => $incident
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'An error occurred while creating the incident',
                'data' => (object)[],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateIncidentNumber()
    {
        $datePart = now()->format('dmY');
        $randomPart = strtoupper(uniqid());

        return $datePart . '-' . substr($randomPart, -5);
    }
}
