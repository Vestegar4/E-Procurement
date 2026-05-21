<?php

use Illuminate\Support\Facades\Route;
use App\Models\Vendor;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;     
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema; 

// DASHBOARD
Route::get('/', function () {
    $vendorCount = Schema::hasTable('vendor') ? DB::table('vendor')->count() : (Schema::hasTable('vendors') ? Vendor::count() : 0);
    $tenderCount = Schema::hasTable('tenders') ? Tender::count() : 0;
    $resultCount = 0;
    $latestTenders = Schema::hasTable('tenders') ? Tender::latest()->take(5)->get() : collect();
    
    return view('dash', compact('vendorCount', 'tenderCount', 'resultCount', 'latestTenders'));
});

Route::get('/users', function () {
    $users = Schema::hasTable('users') ? DB::table('users')->get() : collect(); 
    return view('users', compact('users'));
});

// VENDOR MANAGEMENT
Route::get('/vendors', function () {
    if (Schema::hasTable('vendor')) {
        $vendors = DB::table('vendor')->get();
    } elseif (Schema::hasTable('vendors')) {
        $vendors = Vendor::all();
    } else {
        $vendors = collect();
    }
    return view('vendor', compact('vendors'));
});

// UPDATE STATUS VENDOR
Route::post('/vendors/{id}/update-status', function (Request $request, $id) {
    $table = Schema::hasTable('vendor') ? 'vendor' : (Schema::hasTable('vendors') ? 'vendors' : null);
    if ($table) {
        DB::table($table)->where('id', $id)->update(['status' => $request->status]);
        return back()->with('success', 'Status vendor berhasil diperbarui!');
    }
    return back()->with('error', 'Tabel vendor tidak ditemukan di database Anda.');
});

// PROCUREMENT (Daftar & Tambah Tender)
Route::get('/procurement', function () {
    $tenders = Schema::hasTable('tenders') ? Tender::latest()->get() : collect();
    return view('proc', compact('tenders'));
});

Route::post('/procurement/store', function (Request $request) {
    $adminId = Auth::id();

    if (!$adminId) {
        if (Schema::hasTable('admin')) {
            $firstAdmin = DB::table('admin')->first();
            $adminId = $firstAdmin ? $firstAdmin->id : null;
        } elseif (Schema::hasTable('admins')) {
            $firstAdmin = DB::table('admins')->first();
            $adminId = $firstAdmin ? $firstAdmin->id : null;
        }
    }

    if (!$adminId) {
        return back()->with('error', 'Gagal membuat tender! Akun Admin tidak ditemukan. Mohon pastikan data admin di database Anda sudah terisi.');
    } 

    if (!Schema::hasTable('tenders')) {
        return back()->with('error', 'Gagal membuat tender! Tabel `tenders` tidak ditemukan pada database aktif Anda.');
    }

    Tender::create([
        'title' => $request->title,
        'status' => 'draft',
        'created_by' => $adminId,
    ]);

    return back()->with('success', 'Tender baru berhasil dibuat!');
});

Route::get('/products', function () { return view('products'); });
Route::get('/reports', function () { return view('rep'); });

Route::get('/reports/download/{type}', function ($type) {
    $data = $type == 'procurement' ? (Schema::hasTable('tenders') ? Tender::all() : collect()) : (Schema::hasTable('vendor') ? DB::table('vendor')->get() : collect());
    $filename = ($type == 'procurement' ? "laporan_pengadaan_" : "laporan_keuangan_") . date('Ymd') . ".csv";

    $handle = fopen('php://output', 'w');
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    
    fputcsv($handle, ['ID', 'Nama/Judul', 'Tanggal']);
    foreach ($data as $row) {
        fputcsv($handle, [$row->id, $row->title ?? $row->name, $row->created_at]);
    }
    fclose($handle);
    exit;
});

Route::get('/po', function () { return view('purchaseorder'); });
Route::get('/settings', function () { return view('sett'); });