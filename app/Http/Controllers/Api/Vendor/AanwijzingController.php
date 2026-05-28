<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aanwijzing;
use App\Models\Tender;

class AanwijzingController extends Controller
{
    // Fungsi untuk Ionic mengambil daftar tanya jawab
    public function index($tenderId)
    {
        // 1. MANFAATKAN MODEL TENDER DI SINI
        // Pastikan tender-nya ada. Jika ID tidak ditemukan, Laravel otomatis membalas error 404 Not Found.
        $tender = Tender::findOrFail($tenderId);

        // Ambil semua pertanyaan di tender ini, sertakan juga nama vendornya
        $aanwijzings = Aanwijzing::with('vendor:id,name')
                        ->where('tender_id', $tender->id)
                        ->latest()
                        ->get();

        return response()->json([
            'status' => true,
            'data' => $aanwijzings
        ]);
    }

    // Fungsi untuk Ionic mengirim pertanyaan baru
    public function store(Request $request, $tenderId)
    {
        $request->validate([
            'question' => 'required|string'
        ]);

        // 2. MANFAATKAN MODEL TENDER DI SINI JUGA
        // Pastikan tender valid sebelum sistem mengizinkan vendor bertanya
        $tender = Tender::findOrFail($tenderId);

        // Simpan ke database
        $aanwijzing = Aanwijzing::create([
            'tender_id' => $tender->id,
            'vendor_id' => auth()->id(), // Otomatis mengambil ID vendor yang sedang login
            'question' => $request->question
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pertanyaan berhasil dikirim ke Admin.',
            'data' => $aanwijzing
        ]);
    }
}