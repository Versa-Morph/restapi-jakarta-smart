<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use App\Models\InstanceDetail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data['instances'] = Instance::select(['id', 'name'])->get();
        return view('auth.register', $data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'      => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'instance_id'   => 'required|exists:instances,id',
            'name'          => 'required',
            'pluscode'      => 'required',
            'address'       => 'required',
            'longitude'     => 'required|numeric',
            'latitude'      => 'required|numeric',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'username.required'     => 'Username is required',
            'email.required'        => 'Email is required',
            'email.email'           => 'Email must be a valid email address',
            'email.unique'          => 'This email is already registered',
            'password.required'     => 'Password is required',
            'password.min'          => 'Password must be at least 8 characters',
            'password.confirmed'    => 'Passwords do not match',
            'instance_id.required'  => 'Instance is required',
            'instance_id.exists'    => 'Selected instance does not exist',
            'name.required'         => 'Name is required',
            'pluscode.required'     => 'Plus code is required',
            'address.required'      => 'Address is required',
            'longitude.required'    => 'Longitude is required',
            'longitude.numeric'     => 'Longitude must be a number',
            'latitude.required'     => 'Latitude is required',
            'latitude.numeric'      => 'Latitude must be a number',
            'logo.image'            => 'Logo must be an image',
            'logo.mimes'            => 'Logo must be a file of type: png, jpg, jpeg, webp',
            'logo.max'              => 'Logo may not be greater than 2048 kilobytes',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('instance_details'), $filename);
                $data['logo'] = 'instance_details/' . $filename;
            }

            $dataInstance = InstanceDetail::create([
                'instance_id'  => $data['instance_id'],
                'name'         => $data['name'],
                'pluscode'     => $data['pluscode'],
                'logo'         => $data['logo'] ?? null,
                'address'      => $data['address'],
                'longitude'    => $data['longitude'],
                'latitude'     => $data['latitude'],
            ]);

            // Ensure $dataInstance->id is available
            if ($dataInstance) {
                $user = User::create([
                    'username'  => $data['username'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'role'      => 'instansi',
                    'instance_detail_id'  => $dataInstance->id,
                ]);

                event(new Registered($user));
                Auth::login($user);

                DB::commit();

                return redirect(RouteServiceProvider::HOME);
            } else {
                return redirect()->back()->with('alert', [
                    'type' => 'danger',
                    'message' => 'Failed to create user',
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->route('register')->with('alert', [
                'type' => 'danger',
                'message' => 'Failed to create user',
            ]);
        }
    }
}
