<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserBioController extends Controller
{
    public function upsertUserBio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik'           => 'nullable|string|max:16',
            'profile_pict'  => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'full_name'     => 'nullable|string|max:50',
            'nickname'      => 'nullable|string|max:50',
            'city'          => 'nullable|string|max:50',
            'address'       => 'nullable|string',
            'age'           => 'nullable|integer',
            'blood_type'    => 'nullable|string|max:3',
            'height'        => 'nullable|integer',
            'weight'        => 'nullable|integer',
            'phone_number'  => ['nullable', 'regex:/^08[0-9]{9,11}$/', Rule::unique('user_bio')->ignore(Auth::id(), 'user_id')]
        ]);
        // dd();
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
                'code' => 404,
                'success' => false,
                'message' => 'User not authenticated',
                'data' => (object)[]
            ], 404);
        }

        $userBio = UserBio::firstOrNew(['user_id' => $user->id]);

        $profilePictPath = $userBio->profile_pict_path;
        if ($request->hasFile('profile_pict')) {
            if ($profilePictPath && file_exists(public_path($profilePictPath))) {
                unlink(public_path($profilePictPath));
            }
            $file = $request->file('profile_pict');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile_pictures'), $filename);
            $profilePictPath = 'profile_pictures/' . $filename;
        }

        $userBio->fill([
            'nik'               => $request->nik,
            'profile_pict_path' => $profilePictPath,
            'full_name'         => $request->full_name,
            'nickname'          => $request->nickname,
            'city'              => $request->city,
            'address'           => $request->address,
            'age'               => $request->age,
            'blood_type'        => $request->blood_type,
            'height'            => $request->height,
            'weight'            => $request->weight,
            'phone_number'      => $request->phone_number
        ]);

        $userBio->save();

        $message = $userBio->wasRecentlyCreated ? 'User bio created successfully' : 'User bio updated successfully';

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => $message,
            'data' => $userBio
        ], 200);
    }

    public function getUserBio()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => 'User not authenticated',
                'data' => (object)[]
            ], 404);
        }

        $userBio = $user->userBio;

        if (!$userBio) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => 'User bio not found',
                'data' => (object)[]
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'User bio retrieved successfully',
            'data' => $userBio
        ], 200);
    }
}
