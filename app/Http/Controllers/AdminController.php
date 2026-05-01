<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admins;

class AdminController extends Controller
{
    public function index()
    {
       return response()->json(Admins::all());
    }

    public function show($id)
    {
        $admins = Admins::with('creator')->findOrFail($id);
        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|string|min:8',
            'role' => 'nullable|in:admin,superadmin',
            'is_active' => 'nullable|boolean',
            'last_login_at' => 'nullable|date',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $admins = Admins::create($validated);

        return response()->json([
            'message' => 'Admin created successfully',
            'data' => $admins
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $admins = Admins::findOrFail($id);
        $validated = $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'email'         => 'sometimes|required|email|unique:admin,email,' . $id,
            'password'      => 'sometimes|required|string|min:8',
            'role'          => 'sometimes|nullable|in:admin,superadmin',
            'is_active'     => 'sometimes|nullable|boolean',
            'last_login_at' => 'sometimes|nullable|date',
        ]);
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $admins->update($validated);
        return response()->json([
            'message' => 'Admin updated successfully',
            'data'    => $admins
        ]);
    }

    public function destroy($id)
    {
        $admins = Admins::findOrFail($id);
        $admins->delete();
        return response()->json(['message' => 'Admin berhasil dihapus'], 200);
    }
}
