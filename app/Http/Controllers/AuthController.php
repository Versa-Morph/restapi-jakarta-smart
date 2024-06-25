<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'User successfully registered',
                'data' => [
                    'user' => $user,
                    'access_token' => [
                        'token' => $token,
                        'type' => 'Bearer',
                        'expires_in' => (int)JWTFactory::getTTL() * 60,
                    ],
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Registration Failed',
                'data' => (object)[]
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'success' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors()
            ], 400);
        }

        $credentials = $request->only('email', 'password');

        if (! $token = Auth::attempt($credentials)) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized',
                'data' => (object)[]
            ], 401);
        }

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => (int)JWTFactory::getTTL() * 60,
                ],
            ]
        ], 200);
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Successfully logged out',
                'data' => (object)[]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Logout Failed',
                'data' => (object)[]
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $token = Auth::refresh();
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Token successfully refreshed',
                'data' => ['token' => $token]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Token Refresh Failed',
                'data' => (object)[]
            ], 500);
        }
    }

    public function me()
    {
        try {
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'User data retrieved successfully',
                'data' => Auth::user()
            ], 200);
        } catch (Exception   $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Failed to retrieve user data',
                'data' => (object)[]
            ], 500);
        }
    }

    // =========================================================================
    // SOCIALITE HANDLE

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                ['name' => $socialUser->getName(), 'password' => bcrypt(Str::random(24))]
            );

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Login successful',
                'data' => ['token' => $token]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Authentication Failed',
                'data' => (object)[]
            ], 500);
        }
    }
}
