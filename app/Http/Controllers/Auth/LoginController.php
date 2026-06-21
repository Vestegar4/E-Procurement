<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Override redirect berdasarkan role
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'vendor') {
            // Jika dia Vendor tapi nekat mencoba login lewat Web, langsung TOLAK!
            // Karena Vendor harus login dari Aplikasi Mobile Ionic (API)
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Akses Ditolak: Akun Vendor hanya dapat diakses melalui Aplikasi Mobile Proculus.',
            ]);
        }

        // Fallback (Jika rolenya aneh/tidak terdaftar)
        auth()->logout();
        return redirect()->route('login');
    }

    // --- FITUR BARU: OVERRIDE LOGOUT REDIRECT ---
    protected function loggedOut(Request $request)
    {
        // Lempar user kembali ke halaman utama (landing page) dan munculkan Toast Info
        return redirect('/')->with('info', 'Anda telah berhasil keluar dari portal sistem aplikasi.');
    }
}
