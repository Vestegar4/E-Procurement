<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorManagementController extends Controller
{
    // list vendor vendor
    public function index()
    {
        $vendors = Vendor::latest()->paginate(15);

        return response()->json([
            'message' => 'Vendors retrieved successfully',
            'data' => $vendors
        ]);
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
}
