<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyDetail;
use Exception;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies   = Agency::all();
        $page_title = 'agencies';
        return view('agencies.index', compact('agencies', 'page_title'));
    }

    public function create()
    {
        $page_title = 'create';

        return view('agencies.create',  compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:agencies',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $data = $request->all();

        try {
            if ($request->hasFile('icon')) {
                $file = $request->file('icon');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('agency_icons'), $filename);
                $data['icon'] = 'agency_icons/' . $filename;
            }

            Agency::create($data);
            return redirect()->route('agencies.index')->with('alert', [
                'type' => 'success',
                'message' => 'Agency created successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('agencies.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to created agency: ' . $e->getMessage(),
            ]);
        }
    }

    public function show(Agency $agency)
    {
        $page_title = 'show';
        $agencyDetails = AgencyDetail::where('agency_id', $agency->id)->get();

        return view('agencies.show', compact('agency', 'page_title', 'agencyDetails'));
    }

    public function edit(Agency $agency)
    {
        $page_title = 'edit';

        return view('agencies.edit', compact('agency', 'page_title'));
    }

    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'name' => 'required|unique:agencies,name,' . $agency->id,
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $data = $request->all();

        try {
            if ($request->hasFile('icon')) {
                // Hapus ikon lama jika ada
                if ($agency->icon && file_exists(public_path($agency->icon))) {
                    unlink(public_path($agency->icon));
                }
                $file = $request->file('icon');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('agency_icons'), $filename);
                $data['icon'] = 'agency_icons/' . $filename;
            }

            $agency->update($data);

            return redirect()->route('agencies.index')->with('alert', [
                'type' => 'success',
                'message' => 'Agency updated successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('agencies.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to update agency: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Agency $agency)
    {
        try {
            if ($agency->icon && file_exists(public_path($agency->icon))) {
                unlink(public_path($agency->icon));
            }
            $agency->delete();
            return redirect()->route('agencies.index')->with('alert', [
                'type' => 'success',
                'message' => 'Agency deleted successfully!',
            ]);
        } catch (Exception $e) {
            return redirect()->route('agencies.index')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to delete agency: ' . $e->getMessage(),
            ]);
        }
    }
}
