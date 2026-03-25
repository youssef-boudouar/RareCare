<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,doctor,researcher',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'user'          => $user,
            'access_token'  => $token,
            'refresh_token' => $token,
            'token_type'    => 'Bearer',
            'expires_in'    => auth('api')->factory()->getTTL() * 60,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'user'          => auth('api')->user(),
            'access_token'  => $token,
            'refresh_token' => $token,
            'token_type'    => 'Bearer',
            'expires_in'    => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function profile()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return response()->json([
            'access_token'  => $token,
            'refresh_token' => $token,
            'token_type'    => 'Bearer',
            'expires_in'    => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
