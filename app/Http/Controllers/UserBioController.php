<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserBio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserBioController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:16',
            'profile_pict' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'full_name' => 'required|string|max:50',
            'nickname' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'address' => 'required|string',
            'age' => 'required|integer',
            'blood_type' => 'required|string|max:3',
            'height' => 'required|integer',
            'weight' => 'required|integer'
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
                'code' => 404,
                'success' => false,
                'message' => 'User not authenticated',
                'data' => (object)[]
            ], 404);
        }

        // Check user bio exist
        $existingUserBio = UserBio::where('user_id', $user->id)->first();
        if ($existingUserBio) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'User bio already exists',
                'data' => (object)[]
            ], 400);
        }

        $profilePictPath = null;
        if ($request->hasFile('profile_pict')) {
            $file = $request->file('profile_pict');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profile_pictures'), $filename);
            $profilePictPath = 'profile_pictures/' . $filename;
        }

        $userBio = UserBio::create([
            'user_id' => $user->id,
            'nik' => $request->nik,
            'profile_pict_path' => $profilePictPath,
            'full_name' => $request->full_name,
            'nickname' => $request->nickname,
            'city' => $request->city,
            'address' => $request->address,
            'age' => $request->age,
            'blood_type' => $request->blood_type,
            'height' => $request->height,
            'weight' => $request->weight
        ]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'User bio added successfully',
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|max:16',
            'profile_pict' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'full_name' => 'required|string|max:50',
            'nickname' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'address' => 'required|string',
            'age' => 'required|integer',
            'blood_type' => 'required|string|max:3',
            'height' => 'required|integer',
            'weight' => 'required|integer'
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

        $userBio->update([
            'nik' => $request->nik,
            'profile_pict_path' => $profilePictPath,
            'full_name' => $request->full_name,
            'nickname' => $request->nickname,
            'city' => $request->city,
            'address' => $request->address,
            'age' => $request->age,
            'blood_type' => $request->blood_type,
            'height' => $request->height,
            'weight' => $request->weight
        ]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'User bio updated successfully',
            'data' => $userBio
        ], 200);
    }
}
