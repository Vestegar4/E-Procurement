<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aanwijzing;
use App\Models\Tender;
use App\Models\User;
use App\Notifications\AanwijzingQuestionNotification;

class AanwijzingController extends Controller
{
    // Fungsi untuk Ionic mengambil daftar Tanya Jawab
    public function index($tenderId)
    {
        $tender = Tender::findOrFail($tenderId);

        // Ambil data Tanya Jawab
        $aanwijzings = Aanwijzing::with('vendor')
                        ->where('tender_id', $tender->id)
                        ->latest()
                        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar Tanya Jawab berhasil dimuat', // Diubah di sini
            'data' => $aanwijzings
        ]);
    }

    // Fungsi untuk Ionic mengirim pertanyaan baru
    public function store(Request $request, $tenderId)
    {
        $request->validate([
            'question' => 'required|string'
        ]);

        $tender = Tender::findOrFail($tenderId);

        // Simpan ke database
        $aanwijzing = Aanwijzing::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id, 
            'question' => $request->question
        ]);

        // Kirim notifikasi ke admin
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new AanwijzingQuestionNotification($aanwijzing->vendor->name, $tender->title));

        return response()->json([
            'status' => true,
            'message' => 'Pertanyaan Tanya Jawab berhasil dikirim ke Admin.', // Diubah di sini
            'data' => $aanwijzing
        ]);
    }
}