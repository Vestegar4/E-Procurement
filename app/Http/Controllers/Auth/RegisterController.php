<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // tampil halaman register
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // proses register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',

            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
            'npwp' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {

            // create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'vendor',
            ]);

            // create vendor
            Vendor::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'company_name' => $request->company_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'npwp' => $request->npwp,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('login')->with(
                'success',
                'Registrasi berhasil. Tunggu approval admin.'
            );
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withInput()->withErrors([
                'email' => 'Terjadi kesalahan saat registrasi.'
            ]);
        }
    }
}
