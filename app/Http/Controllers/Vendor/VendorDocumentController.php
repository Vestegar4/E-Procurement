<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Models\VendorDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VendorDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WEB METHODS (Blade)
    |--------------------------------------------------------------------------
    */

    // halaman daftar dokumen vendor
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(404, 'Vendor not found');
        }

        $documents = VendorDocument::where(
            'vendor_id',
            $vendor->id
        )
            ->latest()
            ->paginate(10);

        return view('vendor.documents', compact('documents'));
    }

    // upload dokumen vendor
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:npwp,nib,siup,company_profile,domicile_letter,other',

            'document_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            return back()->with(
                'error',
                'Vendor tidak ditemukan.'
            );
        }

        // cek apakah dokumen tipe ini sudah ada
        $existingDocument = VendorDocument::where([
            'vendor_id' => $vendor->id,
            'document_type' => $request->document_type
        ])->first();

        // upload file
        $file = $request->file('document_file');

        $filePath = $file->store(
            'vendor-documents/vendor-' . $vendor->id,
            'public'
        );

        // kalau sudah ada → replace
        if ($existingDocument) {

            // hapus file lama
            if (
                $existingDocument->file_path &&
                Storage::disk('public')->exists($existingDocument->file_path)
            ) {
                Storage::disk('public')
                    ->delete($existingDocument->file_path);
            }

            $existingDocument->update([
                'document_name' => $file->getClientOriginalName(),

                'file_path' => $filePath,

                'status' => 'pending',

                'notes' => null,

                'uploaded_at' => now(),

                'verified_at' => null,
            ]);

            return redirect()
                ->route('vendor.documents.index')
                ->with(
                    'success',
                    'Dokumen berhasil diperbarui.'
                );
        }

        // create dokumen baru
        VendorDocument::create([
            'vendor_id' => $vendor->id,

            'document_type' => $request->document_type,

            'document_name' => $file->getClientOriginalName(),

            'file_path' => $filePath,

            'status' => 'pending',

            'uploaded_at' => now(),
        ]);

        return redirect()
            ->route('vendor.documents.index')
            ->with(
                'success',
                'Dokumen berhasil diupload.'
            );
    }

    // download dokumen
    public function download($id)
    {
        $vendor = auth()->user()->vendor;

        $document = VendorDocument::where([
            'id' => $id,
            'vendor_id' => $vendor->id
        ])->firstOrFail();

        if (
            !Storage::disk('public')
                ->exists($document->file_path)
        ) {
            return back()->with(
                'error',
                'File tidak ditemukan.'
            );
        }

        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->document_name
            );
    }

    // hapus dokumen
    public function destroy($id)
    {
        $vendor = auth()->user()->vendor;

        $document = VendorDocument::where([
            'id' => $id,
            'vendor_id' => $vendor->id
        ])->firstOrFail();

        // hapus file storage
        if (
            $document->file_path &&
            Storage::disk('public')->exists($document->file_path)
        ) {
            Storage::disk('public')
                ->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('vendor.documents.index')
            ->with(
                'success',
                'Dokumen berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | API METHODS (Mobile / Ionic)
    |--------------------------------------------------------------------------
    */

    // API list documents
    public function apiIndex()
    {
        $vendor = auth()->user()->vendor;

        $documents = VendorDocument::where(
            'vendor_id',
            $vendor->id
        )
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Vendor documents retrieved successfully',
            'data' => $documents
        ]);
    }
}
