<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Tender;
use App\Models\User;
use App\Models\Aanwijzing;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\Admin\AdminNotificationController;

// ============================================================
// AUTH & HOME ROUTES
// ============================================================
Auth::routes(['register' => true]);

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }
    return view('home.home');
})->name('home');

Route::get('/pending-approval', fn() => view('auth.pending'))->name('pending');


// ============================================================
// ADMIN ROUTES 
// ============================================================
Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        $vendorCount = Schema::hasTable('vendors') ? Vendor::count() : 0;
        $tenderCount = Schema::hasTable('tenders') ? Tender::count() : 0;
        $latestTenders = Schema::hasTable('tenders') ? Tender::latest()->take(5)->get() : collect();
        return view('admin.dashboard', compact('vendorCount', 'tenderCount', 'latestTenders'));
    })->name('admin.dashboard');

    Route::get(
        '/notifications',
        [AdminNotificationController::class, 'index']
    )->name('admin.notifications');

    Route::post(
        '/notifications/{id}/read',
        [AdminNotificationController::class, 'markAsRead']
    )->name('admin.notifications.read');

    Route::post(
        '/notifications/read-all',
        [AdminNotificationController::class, 'markAllAsRead']
    )->name('admin.notifications.readAll');

    // ==========================================
    // MODULE: VENDOR (FILTER + SORT BY ID)
    // ==========================================
    Route::get('/vendors', function (Request $request) {
        if (Schema::hasTable('vendors')) {
            // Gunakan with('user') agar memuat relasi email (Mencegah N+1 Query bug)
            $query = Vendor::with('user');

            // 1. Logika Filter Status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // 2. Logika Pencarian (Perbaikan Error Kolom Email)
            if ($request->filled('search')) {
                $search = $request->search;

                // Kondisi pencarian harus dibungkus function() agar tidak merusak filter status
                $query->where(function ($q) use ($search) {
                    // Cari berdasarkan nama perusahaan (Tabel vendors)
                    $q->where('company_name', 'like', '%' . $search . '%')
                        // ATAU cari berdasarkan email (Tabel users) melalui relasi
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('email', 'like', '%' . $search . '%');
                        });
                });
            }

            // Pengurutan berdasarkan ID Ascending
            $vendors = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        } else {
            $vendors = collect();
        }
        return view('admin.vendors', compact('vendors'));
    })->name('admin.vendors');

    Route::post('/vendors/{id}/update-status', function (Request $request, $id) {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => $request->status]);
        return back()->with('success', 'Status verifikasi akun vendor berhasil diperbarui!');
    })->name('admin.vendors.update-status');

    // ==========================================
    // MODULE: PENGADAAN / TENDER (FILTER STATUS ONLY)
    // ==========================================
    Route::get('/procurement', function (Request $request) {
        if (Schema::hasTable('tenders')) {
            $query = Tender::query();

            // Logika Filter Status saja (Tanpa Search)
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Pengurutan berdasarkan ID
            $tenders = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        } else {
            $tenders = collect();
        }
        return view('admin.procurement', compact('tenders'));
    })->name('admin.procurement');

    Route::post('/tenders/store', function (Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'aanwijzing_date' => 'required',
            'aanwijzing_time' => 'required',
            'bidding_start_date' => 'required',
            'bidding_start_time' => 'required',
            'bidding_end_date' => 'required',
            'bidding_end_time' => 'required',
        ]);

        $tender = Tender::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $tender->timeline()->updateOrCreate(
            ['tender_id' => $tender->id],
            [
                'aanwijzing_at' => $request->aanwijzing_date . ' ' . $request->aanwijzing_time,
                'bidding_start' => $request->bidding_start_date . ' ' . $request->bidding_start_time,
                'bidding_end' => $request->bidding_end_date . ' ' . $request->bidding_end_time,
            ]
        );

        return back()->with('success', 'Paket rencana pengadaan tender baru berhasil diterbitkan!');
    })->name('admin.tenders.store');

    Route::put('/tenders/{id}/update', function (Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'aanwijzing_date' => 'required',
            'aanwijzing_time' => 'required',
            'bidding_start_date' => 'required',
            'bidding_start_time' => 'required',
            'bidding_end_date' => 'required',
            'bidding_end_time' => 'required',
        ]);

        $tender = Tender::findOrFail($id);
        $tender->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $tender->timeline()->updateOrCreate(
            ['tender_id' => $tender->id],
            [
                'aanwijzing_at' => $request->aanwijzing_date . ' ' . $request->aanwijzing_time,
                'bidding_start' => $request->bidding_start_date . ' ' . $request->bidding_start_time,
                'bidding_end' => $request->bidding_end_date . ' ' . $request->bidding_end_time,
            ]
        );

        return back()->with('success', 'Perubahan data paket pengadaan lelang berhasil disimpan!');
    })->name('admin.tenders.update');

    Route::get('/tenders/{id}/bids', function ($id) {
        $tender = \App\Models\Tender::with('bids')->findOrFail($id);
        $bids = $tender->bids;
        $result = $tender->result;
        return view('admin.tender-bids', compact('tender', 'bids', 'result'));
    })->name('admin.tenders.bids');

    Route::post('/tenders/{id}/select-winner', function (Request $request, $id) {
        return back()->with('success', 'Pemenang lelang paket pengadaan berhasil ditetapkan.');
    })->name('admin.tenders.select-winner');

    // ==========================================
    // MODULE: AANWIJZING
    // ==========================================
    Route::get('/tenders/{id}/aanwijzing', function ($id) {
        $tender = Tender::with('aanwijzings.vendor')->findOrFail($id);
        return view('admin.aanwijzing', compact('tender'));
    })->name('admin.tenders.aanwijzing');

    Route::post('/aanwijzing/{id}/jawab', function (Request $request, $id) {
        $request->validate(['answer' => 'required|string']);
        $aanwijzing = Aanwijzing::findOrFail($id);
        $aanwijzing->update(['answer' => $request->answer]);
        return back()->with('interaction', 'Jawaban penjelasan resmi berhasil diterbitkan ke forum publik!');
    })->name('admin.aanwijzing.jawab');

    // ==========================================
    // MODULE: PURCHASE ORDERS (FILTER + SORT BY ID)
    // ==========================================
    Route::get('/purchase-orders', function (Request $request) {
        if (Schema::hasTable('purchase_orders')) {
            $query = \App\Models\PurchaseOrder::with(['tender', 'vendor']);

            // Logika Filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $query->where('id', 'like', '%' . $request->search . '%');
            }

            // Pengurutan berdasarkan ID Ascending
            $purchaseOrders = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        } else {
            $purchaseOrders = collect();
        }
        return view('admin.purchase-order', compact('purchaseOrders'));
    })->name('admin.purchase-order');

    Route::get('/purchase-orders/{id}/export-pdf', [PurchaseOrderController::class, 'exportPDF'])->name('admin.purchase-orders.pdf');

    // ==========================================
    // MODULE: PRODUCTS (KATALOG BARANG)
    // ==========================================
    Route::get('/products', function (Request $request) {
        if (Schema::hasTable('products')) {
            $query = \App\Models\Product::query();

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $products = $query->latest()->paginate(10)->withQueryString();
        } else {
            $products = collect();
        }
        return view('admin.products', compact('products'));
    })->name('admin.products');

    // TAMBAHKAN RUTE INI UNTUK MENYIMPAN BARANG BARU (POST)
    Route::post('/products', function (Request $request) {
        // Validasi inputan form
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_in' => 'required|integer|min:0',
            'stock_out' => 'required|integer|min:0',
        ]);

        // Simpan ke database
        \App\Models\Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'stock_in' => $request->stock_in,
            'stock_out' => $request->stock_out,
        ]);

        return back()->with('success', 'Barang baru berhasil ditambahkan ke katalog!');
    })->name('admin.products.store');

    // OTHERS MASTER DATA
    Route::get('/users', function () {
        $users = Schema::hasTable('users') ? User::whereIn('role', ['admin', 'super_admin'])->get() : collect();
        return view('admin.users', compact('users'));
    })->name('admin.users');

    // Rute untuk Halaman Laporan & Grafik (Ini yang sudah kamu buat, sudah benar!)
    Route::get('/reports', function () {
        // data untuk chart keuangan (total anggaran pembelian per bulan di tahun berjalan)
        $financialData = \Illuminate\Support\Facades\DB::table('purchase_orders')
            ->select(
                \Illuminate\Support\Facades\DB::raw("DATE_FORMAT(created_at, '%b') as month_name"),
                \Illuminate\Support\Facades\DB::raw("SUM(total_amount) as total_budget")
            )
            ->whereYear('created_at', date('Y')) // Hanya mengambil data tahun berjalan
            ->groupBy(\Illuminate\Support\Facades\DB::raw("DATE_FORMAT(created_at, '%b')"), \Illuminate\Support\Facades\DB::raw("MONTH(created_at)"))
            ->orderBy(\Illuminate\Support\Facades\DB::raw("MONTH(created_at)"), 'asc')
            ->get();

        // Ekstrak nama bulan dan total anggaran ke dalam array untuk Chart.js
        $chartLabels = $financialData->pluck('month_name')->toArray();
        $chartValues = $financialData->pluck('total_budget')->map(function ($value) {
            return (float) $value;
        })->toArray();

        // Jika tidak ada data, set default agar chart tetap tampil dengan nilai 0
        if (empty($chartLabels)) {
            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
            $chartValues = [0, 0, 0, 0, 0, 0];
        }

        // Kirim data asli database ke file view admin/reports.blade.php
        return view('admin.reports', compact('chartLabels', 'chartValues'));
    })->name('admin.reports');

    // menambahkan rute ini agar tidak error
    Route::get('/reports/download/{type}', function ($type) {
        return back()->with('success', 'File data berhasil diekstrak.');
    })->name('admin.reports.download');
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
});
