<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorManagementController extends Controller
{
    // list vendor vendor
    public function index(Request $Request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        // 2. Query data vendor (dan relasi dokumennya sekalian)
        $vendors = Vendor::with('user', 'documents')
            ->when($search, function ($query) use ($search) {
                $query->where('company_name', 'like', '%' . $search . '%');
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // 3. Kembalikan ke tampilan Web, BUKAN ke JSON
        return view('admin.vendors', compact('vendors'));
    }

    // detail vendor
    public function show($id)
    {
        $vendor = Vendor::with('documents')
            ->findOrFail($id);

        return response()->json([
            'message' => 'Vendor detail retrieved successfully',
            'data' => $vendor
        ]);
    }

    // mengapruv vendor
    public function approve($id)
    {
        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        return response()->json([
            'message' => 'Vendor approved successfully'
        ]);
    }

    // reject vendor
    public function reject($id)
    {
        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'message' => 'Vendor rejected'
        ]);
    }

    // update status vendor via admin panel
    public function updateStatus(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $vendor->status = $validated['status'];
        $vendor->approved_at = $validated['status'] === 'approved' ? now() : null;
        $vendor->save();

        return back()->with('success', 'Status vendor berhasil diperbarui.');
    }
}
