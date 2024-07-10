<?php

namespace App\Http\Controllers;

use App\Models\UserBio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\UserContact;
use Illuminate\Support\Facades\DB;

class UserContactController extends Controller
{
    public function getUserContact()
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

        $userContacts = UserContact::where('user_id', $user->id)->get();

        if (!$userContacts || ($userContacts->count() == 0) || empty($userContacts)) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => 'User contact not found',
                'data' => (object)[]
            ], 404);
        }

        $data = [];

        foreach ($userContacts as $userContact) {
            $userBio = UserBio::where('user_id', $userContact->user_contact_id)->first();

            $data[] = $userBio;
        }

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'User contacts retrieved successfully',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_danger'     => 'nullable|boolean',
            'phone_number'  => 'required|regex:/^08[0-9]{9,11}$/'
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
            $userBio = UserBio::where('phone_number', $request->phone_number)->first();

            // Check if user exist
            if (!$userBio || empty($userBio)) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Contact not found',
                    'data' => (object)[]
                ], 404);
            }

            // Check if the user is trying to add themselves as a contact
            if ($user->id === $userBio->user_id) {
                return response()->json([
                    'code' => 400,
                    'success' => false,
                    'message' => 'You cannot add yourself as a contact',
                    'data' => (object)[]
                ], 400);
            }

            $userContact = UserContact::where('user_id', $user->id)->where('user_contact_id', $userBio->user_id)->first();

            // Check if the contact already exists
            if ($userContact) {
                return response()->json([
                    'code' => 400,
                    'success' => false,
                    'message' => 'Contact is already registered',
                    'data' => (object)[]
                ], 400);
            }

            $userContact = UserContact::create([
                'user_id' => $user->id,
                'user_contact_id' => $userBio->user_id,
                'is_danger' => $request->is_danger ?? false
            ]);
            DB::commit();

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'User contact created successfully',
                'data' => $userBio
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'An error occurred while creating the user contact',
                'data' => (object)[],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_danger' => 'nullable|boolean'
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

        $userContact = UserContact::where('user_id', $user->id)->where('id', $id)->first();

        if (!$userContact) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => 'User contact not found',
                'data' => (object)[]
            ], 404);
        }

        $userContact->update([
            'is_danger' => $request->is_danger
        ]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'User contact updated successfully',
            'data' => $userContact
        ], 200);
    }

    public function destroy($id)
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

        $userContact = UserContact::where('user_id', $user->id)->where('user_contact_id', $id)->first();

        // Check if contact exist
        if (!$userContact) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => 'User contact not found',
                'data' => (object)[]
            ], 404);
        }

        $userContact->delete();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'User contact deleted successfully',
            'data' => (object)[]
        ], 200);
    }
}
