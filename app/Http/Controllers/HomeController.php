<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['storeContact']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function storeContact(Request $request)
    {
        // Validasi isian form dari landing page
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string'
        ]);

        // Simpan ke tabel CustomerService TANPA vendor_id
        \App\Models\CustomerService::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'status' => 'unread'
        ]);

        // Kembalikan ke landing page dengan pesan sukses
        return back()->with('success', 'Terima kasih! Pesan/Pengaduan Anda telah berhasil dikirim.');
    }
}