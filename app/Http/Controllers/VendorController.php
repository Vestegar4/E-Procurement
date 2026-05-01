<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        return response()->json(Vendor::all());
    }

    public function show($id)
    {
        return response()->json(Vendor::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:vendor,email',
            'password'     => 'required|string|min:8',
            'company_name' => 'required|string|max:255',
            'address'      => 'required|string',
            'phone'        => 'required|string|max:255',
            'npwp'         => 'nullable|string|max:255',
            'status'       => 'nullable|in:pending,approved,rejected',
        ]);

        // Simpan sebagai teks biasa (tanpa hash)
        $vendor = Vendor::create($validated);

        return response()->json([
            'message' => 'Vendor berhasil didaftarkan sesuai struktur tabel baru',
            'data'    => $vendor
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'email'        => 'sometimes|email|unique:vendor,email,' . $id,
            'password'     => 'sometimes|string|min:8',
            'company_name' => 'sometimes|string|max:255',
            'address'      => 'sometimes|string',
            'phone'        => 'sometimes|string|max:255',
            'npwp'         => 'nullable|string|max:255',
            'status'       => 'sometimes|in:pending,approved,rejected',
        ]);

        $vendor->update($validated);

        return response()->json([
            'message' => 'Data vendor berhasil diperbarui',
            'data'    => $vendor
        ]);
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return response()->json(['message' => 'Vendor berhasil dihapus'], 200);
    }
}