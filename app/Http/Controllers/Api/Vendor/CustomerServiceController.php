<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerService;

class CustomerServiceController extends Controller
{
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor tidak ditemukan'], 404);
        }

        // Ambil semua riwayat chat vendor ini dari database
        $chats = CustomerService::where('vendor_id', $vendor->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }
    
    public function store(Request $request)
    {
        // Validasi pesan tidak boleh kosong
        $request->validate([
            'message' => 'required|string'
        ]);

        // Ambil data vendor yang sedang login di HP
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            return response()->json(['message' => 'Profil vendor tidak ditemukan.'], 404);
        }

        // Simpan pengaduan ke database
        CustomerService::create([
            'vendor_id' => $vendor->id,
            'message' => $request->message,
            'status' => 'unread' // Status default: belum dibaca admin
        ]);

        return response()->json([
            'message' => 'Pengaduan berhasil dikirim ke Admin.'
        ], 201);
    }
}