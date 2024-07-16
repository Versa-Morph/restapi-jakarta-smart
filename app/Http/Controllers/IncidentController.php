<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incidents   = Incident::where('status', '!=', 'requested')->get();
        $page_title = 'incidents';

        return view('incidents.index', compact('incidents', 'page_title'));
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

            $incidents = Incident::where('status', '!=', 'requested')
                                ->orderBy('created_at', 'DESC')
                                ->where('user_id', $user->id)
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
                                    ->where('user_id', $user->id)
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
}
