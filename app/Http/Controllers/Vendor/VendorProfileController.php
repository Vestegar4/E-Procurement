<?php

namespace App\Http\Controllers\Vendor;

use App\Models\VendorDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorProfileController extends Controller
{
    // get my profile
    public function me(Request $request)
    {
        $user = $request->user();
        $vendor = $user->vendor->load('documents');

        return response()->json([
            'message' => 'Vendor profile retrieved successfully',
            'data' => [
                'user' => $user,
                'vendor' => $vendor
            ]
        ]);
    }

    // update profile
    public function update(Request $request)
    {
        $user = $request->user();
        $vendor = $user->vendor;

        $request->validate([
            'name' => 'sometimes|string|max:255',

            'company_name' => 'sometimes|string|max:255',

            'address' => 'sometimes|string',

            'phone' => 'sometimes|string|max:50',

            'npwp' => 'nullable|string|max:100',
        ]);

        // Update user fields
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        // Update vendor fields
        $vendor->update([
            'company_name' => $request->company_name ?? $vendor->company_name,

            'address' => $request->address ?? $vendor->address,

            'phone' => $request->phone ?? $vendor->phone,

            'npwp' => $request->npwp ?? $vendor->npwp,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user,
                'vendor' => $vendor
            ]
        ]);
    }

    // upload document
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',

            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();
        $vendor = $user->vendor;

        // store file in storage/app/public/vendor-documents
        $path = $request->file('file')
            ->store('vendor-documents', 'public');


        // create document
        $document = VendorDocument::create([
            'vendor_id' => $vendor->id,

            'document_name' => $request->document_name,

            'file_path' => $path,

            'uploaded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'data' => $document
        ], 201);
    }

    // list documents
    public function documents(Request $request)
    {
        $user = $request->user();
        $vendor = $user->vendor;

        $documents = VendorDocument::where(
            'vendor_id',
            $vendor->id
        )
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Documents retrieved successfully',
            'data' => $documents
        ]);
    }
}
