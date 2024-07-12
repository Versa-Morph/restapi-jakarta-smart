<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyDetail;
use Exception;
use Illuminate\Http\Request;

class AgencyDetailController extends Controller
{
    public function create(Agency $agency)
    {
        $page_title = 'create';
        return view('agencies.details.create', compact('agency', 'page_title'));
    }

    public function store(Request $request, Agency $agency)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'name'      => 'required',
            'address'   => 'required',
            'longitude' => 'required|numeric',
            'latitude'  => 'required|numeric',
            'logo'      => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('agency_details'), $filename);
                $data['logo'] = 'agency_details/' . $filename;
            }

            AgencyDetail::create($data);

            return redirect()->route('agencies.show', $agency->id)->with('alert', [
                'type' => 'success',
                'message' => 'Agency Detail created successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('agency-details.create', $agency->id)->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to create agency detail: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(Agency $agency, AgencyDetail $agencyDetail)
    {
        $page_title = 'edit';
        return view('agencies.details.edit', compact('agency', 'agencyDetail', 'page_title'));
    }

    public function update(Request $request, Agency $agency, AgencyDetail $agencyDetail)
    {
        $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'name'      => 'required',
            'address'   => 'required',
            'longitude' => 'required|numeric',
            'latitude'  => 'required|numeric',
            'logo'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('logo')) {
                // Hapus ikon lama jika ada
                if ($agencyDetail->logo && file_exists(public_path($agencyDetail->logo))) {
                    unlink(public_path($agencyDetail->logo));
                }
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('agency_details'), $filename);
                $data['logo'] = 'agency_details/' . $filename;
            } else {
                // Jika tidak ada file yang diunggah, tetap gunakan logo yang ada
                $data['logo'] = $agencyDetail->logo;
            }

            $agencyDetail->update($data);

            return redirect()->route('agencies.show', $agency->id)->with('alert', [
                'type' => 'success',
                'message' => 'Agency Detail updated successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('agencies.show', $agency->id)->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to update agency detail: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Agency $agency, AgencyDetail $agencyDetail)
    {
        try {
            if ($agencyDetail->logo && file_exists(public_path($agencyDetail->logo))) {
                unlink(public_path($agencyDetail->logo));
            }
            $agencyDetail->delete();
            return redirect()->route('agencies.show', $agency->id)->with('success', 'Agency Detail deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('agencies.show', $agency->id)->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to delete agency detail: ' . $e->getMessage(),
            ]);
        }
    }
}

