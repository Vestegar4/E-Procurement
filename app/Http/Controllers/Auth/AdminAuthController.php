<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AdminAuthController extends Controller
{
    // login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Admin not found'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if admin record is active
        $admin = $user->admin;
        if (!$admin || !$admin->is_active) {
            return response()->json([
                'message' => 'Admin inactive'
            ], 403);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        $user->update([
            'last_login_at' => now()
        ]);

        return response()->json([
            'message' => 'Login success',
            'token' => $token,
            'data' => $user
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout success'
        ]);
    }

    // profile
    public function me(Request $request)
    {
        $user = $request->user();
        $admin = $user->admin;

        return response()->json([
            'message' => 'Admin profile retrieved successfully',
            'data' => [
                'user' => $user,
                'admin' => $admin
            ]
        ]);
    }
}
