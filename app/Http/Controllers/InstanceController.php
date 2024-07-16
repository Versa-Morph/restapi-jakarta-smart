<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\InstanceDetail;
use Exception;
use Illuminate\Http\Request;

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
}
