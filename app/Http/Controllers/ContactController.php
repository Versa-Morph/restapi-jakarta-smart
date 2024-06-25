<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function addContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'nullable|string|email|max:255'
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

        // Cek apakah nomor telepon ada di data seluruh pengguna
        $existingUser = User::where('phone', $request->phone)->first();
        if (!$existingUser) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'Phone number does not match any user',
                'data' => (object)[]
            ], 400);
        }

        // Cek apakah kontak yang ditambahkan bukan dirinya sendiri berdasarkan nomor telepon
        if ($request->phone == $user->phone) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'Cannot add yourself as a contact',
                'data' => (object)[]
            ], 400);
        }

        $contact = Contact::create([
            'user_id'   => $user->id,
            'name'      => $request->name,
            'phone'     => $request->phone,
            'email'     => $request->email
        ]);

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Contact added successfully',
            'data' => $contact
        ], 200);
    }

    public function getContacts()
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

        $contacts = $user->contacts;

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Contacts retrieved successfully',
            'data' => $contacts
        ], 200);
    }
}
