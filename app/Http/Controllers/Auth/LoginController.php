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
            // Cek apakah vendor sudah approved
            $vendor = $user->vendor;

            if (!$vendor || $vendor->status === 'pending') {
                auth()->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Akun vendor kamu masih menunggu persetujuan admin.',
                ]);
            }

            if ($vendor->status === 'rejected') {
                auth()->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Akun vendor kamu telah ditolak. Hubungi admin.',
                ]);
            }

            return redirect()->route('vendor.dashboard');
        }

        // Fallback
        return redirect()->route('login');
    }
}