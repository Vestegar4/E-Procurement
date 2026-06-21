<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCustomerServiceController extends Controller
{
    public function index()
    {
        $pengaduans = \App\Models\CustomerService::with('vendor.user')
            ->orderByRaw("FIELD(status, 'unread', 'read', 'resolved')")
            ->latest()
            ->paginate(15);

        // Ubah status jadi 'read' saat admin membuka halaman ini
        \App\Models\CustomerService::where('status', 'unread')->update(['status' => 'read']);

        // Arahkan ke file blade desain web admin Anda
        return view('admin.customer-service', compact('pengaduans'));
    }

    public function resolve($id)
    {
        $cs = \App\Models\CustomerService::findOrFail($id);
        $cs->update(['status' => 'resolved']);
        return back()->with('success', 'Pengaduan telah ditandai selesai.');
    }
    /**
     * Memuat riwayat tiket seolah-olah obrolan chat
     */
    public function history($vendor_id)
    {
        // Ambil semua tiket dari vendor ini, urutkan dari yang terlama ke terbaru
        $chats = \App\Models\CustomerService::where('vendor_id', $vendor_id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }

    /**
     * Membalas pesan via AJAX (Live Chat UI)
     */
    public function send(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'receiver_id' => 'required',
            'message' => 'required|string'
        ]);

        // 1. Cari tiket terakhir dari vendor ini yang BELUM dibalas admin
        $ticket = \App\Models\CustomerService::where('vendor_id', $request->receiver_id)
                    ->whereNull('admin_reply')
                    ->orderBy('created_at', 'desc')
                    ->first();

        if ($ticket) {
            // Jika ada tiket gantung, isi kolom admin_reply
            $ticket->update([
                'admin_reply' => $request->message,
                'status' => 'answered'
            ]);
        } else {
            // Jika tidak ada tiket gantung, buat baris baru murni dari admin
            \App\Models\CustomerService::create([
                'vendor_id' => $request->receiver_id,
                'message' => '-', // Bypass jika kolom ini required di DB
                'admin_reply' => $request->message,
                'status' => 'answered'
            ]);
        }

        return response()->json(['success' => true]);
    }
}