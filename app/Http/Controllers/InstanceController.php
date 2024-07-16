<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\InstanceDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstanceController extends Controller
{
    public function index()
    {
        $instances   = Instance::all();
        $page_title = 'instances';

        return view('instances.index', compact('instances', 'page_title'));
    }

    public function create()
    {
        $page_title = 'create';

        return view('instances.create',  compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:instances',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $data = $request->all();

        try {
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('intance_icons'), $filename);
                $data['icon'] = 'intance_icons/' . $filename;
            }

            Instance::create($data);
            return redirect()->route('instances.index')->with('alert', [
                'type' => 'success',
                'message' => 'Instance created successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('instances.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to created instance: ' . $e->getMessage(),
            ]);
        }
    }

    public function show(Instance $instance)
    {
        $page_title = 'show';
        $instanceDetails = InstanceDetail::where('instance_id', $instance->id)->get();

        return view('instances.show', compact('instance', 'page_title', 'instanceDetails'));
    }

    public function edit(Instance $instance)
    {
        $page_title = 'edit';

        return view('instances.edit', compact('instance', 'page_title'));
    }

    public function update(Request $request, Instance $instance)
    {
        $request->validate([
            'name' => 'required|unique:instances,name,' . $instance->id,
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $data = $request->all();

        try {
            if ($request->hasFile('icon')) {
                // Hapus ikon lama jika ada
                if ($instance->icon && file_exists(public_path($instance->icon))) {
                    unlink(public_path($instance->icon));
                }

                $file = $request->file('icon');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('instance_icons'), $filename);
                $data['icon'] = 'instance_icons/' . $filename;
            }

            $instance->update($data);

            return redirect()->route('instances.index')->with('alert', [
                'type' => 'success',
                'message' => 'Instance updated successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('instances.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to update instance: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Instance $instance)
    {
        try {
            if ($instance->icon && file_exists(public_path($instance->icon))) {
                unlink(public_path($instance->icon));
            }
            $instance->delete();
            return redirect()->route('instances.index')->with('alert', [
                'type' => 'success',
                'message' => 'Instance deleted successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('instances.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to delete instance: ' . $e->getMessage(),
            ]);
        }
    }


    // =========================================================================================
    // Instance API Controller
    // =========================================================================================

    public function apiGetAllInstances()
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

            $instances = Instance::orderBy('created_at', 'DESC')->get();

            if ($instances->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Instances not found',
                    'data' => (object)[]
                ], 404);
            }

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Instances retrieved successfully',
                'data' => $instances
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

    public function apiGetInstancesByDetail()
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

            $instances = Instance::with('details')->orderBy('created_at', 'DESC')->get();

            if ($instances->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Instances not found',
                    'data' => (object)[]
                ], 404);
            }

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Instances retrieved successfully',
                'data' => $instances
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
