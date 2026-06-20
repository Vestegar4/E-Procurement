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
}