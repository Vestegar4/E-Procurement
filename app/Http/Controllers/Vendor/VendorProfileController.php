<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Models\VendorDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class VendorProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WEB METHODS (Blade)
    |--------------------------------------------------------------------------
    */

    // halaman settings/profile vendor
    public function index()
    {
        $user = auth()->user();

        $vendor = $user->vendor;

        if (!$vendor) {
            abort(404, 'Vendor not found');
        }

        return view('vendor.settings', compact(
            'user',
            'vendor'
        ));
    }

    // update profile dari blade
    public function updateProfileWeb(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'npwp' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();

        $vendor = $user->vendor;

        if (!$vendor) {
            return back()->with(
                'error',
                'Vendor tidak ditemukan.'
            );
        }

        // update user
        $user->update([
            'name' => $request->name,
        ]);

        // update vendor
        $vendor->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'phone' => $request->phone,
            'npwp' => $request->npwp,
        ]);

        return back()->with(
            'success',
            'Profil vendor berhasil diperbarui.'
        );
    }

    // update password dari blade
    public function updatePasswordWeb(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // cek password lama
        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {

            return back()->with(
                'error',
                'Password lama tidak sesuai.'
            );
        }

        // update password
        $user->update([
            'password' => Hash::make(
                $request->new_password
            )
        ]);

        return back()->with(
            'success',
            'Password berhasil diperbarui.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | API METHODS (Mobile / Ionic)
    |--------------------------------------------------------------------------
    */

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

    // update profile API/mobile
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

        // update user fields
        if ($request->has('name')) {

            $user->update([
                'name' => $request->name
            ]);
        }

        // update vendor fields
        $vendor->update([
            'company_name' => $request->company_name
                ?? $vendor->company_name,

            'address' => $request->address
                ?? $vendor->address,

            'phone' => $request->phone
                ?? $vendor->phone,

            'npwp' => $request->npwp
                ?? $vendor->npwp,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user,
                'vendor' => $vendor
            ]
        ]);
    }

    // upload document API/mobile
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
            ->store(
                'vendor-documents/vendor-' . $vendor->id,
                'public'
            );

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

    // list documents API/mobile
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

    // delete document API/mobile
    public function deleteDocument($id)
    {
        $vendor = auth()->user()->vendor;

        $document = VendorDocument::where([
            'id' => $id,
            'vendor_id' => $vendor->id
        ])->firstOrFail();

        // delete file
        if (
            $document->file_path &&
            Storage::disk('public')->exists($document->file_path)
        ) {
            Storage::disk('public')
                ->delete($document->file_path);
        }

        // delete database record
        $document->delete();

        return response()->json([
            'message' => 'Document deleted successfully'
        ]);
    }
}
