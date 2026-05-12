<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class VendorAuthController extends Controller
{
    // register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'npwp' => 'nullable|string|max:100',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'vendor',
        ]);

        // Create Vendor
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'npwp' => $request->npwp,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Vendor registered successfully',
            'data' => [
                'user' => $user,
                'vendor' => $vendor
            ]
        ], 201);
    }

    // login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'vendor')
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Vendor not found'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if vendor is approved
        $vendor = $user->vendor;
        if ($vendor->status !== 'approved') {
            return response()->json([
                'message' => 'Vendor not approved yet'
            ], 403);
        }

        $token = $user->createToken('vendor-token')->plainTextToken;

        $user->update([
            'last_login_at' => now()
        ]);

        return response()->json([
            'message' => 'Login success',
            'token' => $token,
            'data' => $user
        ]);
    }

    // melogout
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
        $vendor = $user->vendor;

        return response()->json([
            'message' => 'Vendor profile retrieved successfully',
            'data' => [
                'user' => $user,
                'vendor' => $vendor
            ]
        ]);
    }
}
