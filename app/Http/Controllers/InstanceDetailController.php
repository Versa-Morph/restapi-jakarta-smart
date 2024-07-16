<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Models\InstanceDetail;
use Exception;
use Illuminate\Http\Request;

class InstanceDetailController extends Controller
{
    public function create(Instance $instance)
    {
        $page_title = 'create';
        return view('instances.details.create', compact('instance', 'page_title'));
    }

    public function store(Request $request, Instance $instance)
    {
        $request->validate([
            'instance_id' => 'required|exists:instances,id',
            'name'        => 'required',
            'address'     => 'required',
            'longitude'   => 'required|numeric',
            'latitude'    => 'required|numeric',
            'logo'        => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('instance_details'), $filename);
                $data['logo'] = 'instance_details/' . $filename;
            }

            InstanceDetail::create($data);

            return redirect()->route('instances.show', $instance->id)->with('alert', [
                'type' => 'success',
                'message' => 'Instance Detail created successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('instance-details.create', $instance->id)->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to create instance detail: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Instance $instance, InstanceDetail $instanceDetail)
    {
        $page_title = 'edit';
        return view('instances.details.edit', compact('instance', 'instanceDetail', 'page_title'));
    }

    public function update(Request $request, Instance $instance, InstanceDetail $instanceDetail)
    {
        $request->validate([
            'instance_id' => 'required|exists:instances,id',
            'name'      => 'required',
            'address'   => 'required',
            'longitude' => 'required|numeric',
            'latitude'  => 'required|numeric',
            'logo'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('logo')) {
                if ($instanceDetail->logo && file_exists(public_path($instanceDetail->logo))) {
                    unlink(public_path($instanceDetail->logo));
                }
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('instance_details'), $filename);
                $data['logo'] = 'instance_details/' . $filename;
            } else {
                $data['logo'] = $instanceDetail->logo;
            }

            $instanceDetail->update($data);

            return redirect()->route('instances.show', $instance->id)->with('alert', [
                'type' => 'success',
                'message' => 'Instance Detail updated successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('instances.show', $instance->id)->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to update instance detail: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Instance $instance, InstanceDetail $instanceDetail)
    {
        try {
            if ($instanceDetail->logo && file_exists(public_path($instanceDetail->logo))) {
                unlink(public_path($instanceDetail->logo));
            }
            $instanceDetail->delete();
            return redirect()->route('instances.show', $instance->id)->with('success', [
                'type' => 'success',
                'message' => 'Instance Detail deleted successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('instances.show', $instance->id)->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to delete instance detail: ' . $e->getMessage(),
            ]);
        }
    }
}
