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
use Illuminate\Support\Facades\Hash;


// AUTH & HOME ROUTES
// Matikan fitur pendaftaran, lupa password, dan verifikasi email
Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false,
]);

Route::get('/', function () {
    // 1. Cek dulu apakah ada session aktif
    if (auth()->check()) {
        $user = auth()->user();
        
        // 2. PELINDUNG "GHOST SESSION": Jika nyangkut tapi akun aslinya hilang/null
        if (!$user) {
            Auth::logout();
            return redirect()->route('home');
        }

        // 3. Jika akunnya benar-benar ada, baru cek Role-nya
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            // Tolak akses jika selain Admin (misal: Vendor)
            Auth::logout();
            return redirect('/')->with('error', 'Akses Ditolak: Akun Vendor hanya dapat diakses melalui Aplikasi Mobile Proculus.');
        }
    }
    
    // Jika memang belum login, tampilkan landing page (beranda)
    return view('home.home');
})->name('home');

// ADMIN ROUTES 
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->prefix('admin')->group(function () {
    

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

    // MODULE: VENDOR (FILTER + SORT BY ID)
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

    // MODULE: PENGADAAN / TENDER (FILTER STATUS ONLY)
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
            'created_by' => auth()->id(),
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


    // Rute API untuk menetapkan pemenang
    Route::post('/tenders/{id}/select-winner', function (Request $request, $id) {
        // 1. Validasi input dari Modal
        $request->validate([
            'bid_id' => 'required|exists:bids,id',
            'vendor_id' => 'required|exists:vendors,id',
            'notes' => 'required|string',
        ]);

        // 2. Simpan Data Pemenang (Tanpa memasukkan bid_id ke tabel tender_results)
        $result = new \App\Models\TenderResult();
        $result->tender_id = $id;
        $result->winner_vendor_id = $request->vendor_id;
        $result->notes = $request->notes;
        // Kolom bid_id dihapus dari sini karena tidak ada di struktur database Anda
        $result->save();

        // 3. Ubah status Tender menjadi 'completed' (Selesai/Diumumkan)
        $tender = \App\Models\Tender::findOrFail($id);
        $tender->update([
            'status' => 'completed'
        ]);

        // 4. Update status dokumen penawaran (bids) agar label "Pemenang" vs "Gugur" muncul
        if (\Illuminate\Support\Facades\Schema::hasColumn('bids', 'status')) {
            // Tandai vendor terpilih sebagai pemenang ('won' / 'approved')
            \App\Models\Bid::where('id', $request->bid_id)->update(['status' => 'won']);

            // Tandai vendor lainnya yang ikut tender ini sebagai gugur ('lost' / 'rejected')
            \App\Models\Bid::where('tender_id', $id)
                ->where('id', '!=', $request->bid_id)
                ->update(['status' => 'lost']);
        }

        return back()->with('success', 'Pemenang lelang berhasil ditetapkan dan langsung diumumkan!');
    })->name('admin.tenders.select-winner');

    // MODULE: AANWIJZING 
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

    // MODULE: PURCHASE ORDERS (FILTER + SORT BY ID)
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
    Route::put('/purchase-orders/{id}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('admin.purchase-orders.update-status');

    // MODULE: OTHERS MASTER DATA (MANAJEMEN USER - DIURUTKAN BERDASARKAN ROLE)
    Route::get('/users', function (Request $request) {
        if (Schema::hasTable('users')) {
            $query = User::query();

            // 1. Logika Filter Berdasarkan Role
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // 2. Logika Pencarian berdasarkan nama atau email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // KUNCI PERBAIKAN: Urutkan berdasarkan 'role' dari A ke Z, 
            // lalu urutkan berdasarkan 'name' agar nama di dalam role yang sama juga berurutan.
            $users = $query->orderBy('role', 'asc')
                ->orderBy('name', 'asc')
                ->paginate(10)
                ->withQueryString();
        } else {
            $users = collect();
        }

        return view('admin.users', compact('users'));
    })->name('admin.users');

    // Laporan & Grafik Keuangan
    // Rute untuk Halaman Laporan & Grafik (Ini yang sudah kamu buat, sudah benar!)
    Route::get('/reports', function (\Illuminate\Http\Request $request) {
        $selectedYear = $request->input('year', date('Y'));

        // Dapatkan list tahun yang tersedia dari database untuk dropdown
        $availableYears = \Illuminate\Support\Facades\DB::table('purchase_orders')
            ->selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Jika tidak ada data, pastikan minimal ada tahun sekarang
        if (empty($availableYears)) {
            $availableYears = [date('Y')];
        }

        // data untuk chart keuangan (total anggaran pembelian per bulan di tahun berjalan)
        $financialData = \Illuminate\Support\Facades\DB::table('purchase_orders')
            ->select(
                \Illuminate\Support\Facades\DB::raw("DATE_FORMAT(created_at, '%b') as month_name"),
                \Illuminate\Support\Facades\DB::raw("SUM(total_amount) as total_budget")
            )
            ->whereYear('created_at', $selectedYear) // Hanya mengambil data sesuai tahun terpilih
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
        return view('admin.reports', compact('chartLabels', 'chartValues', 'selectedYear', 'availableYears'));
    })->name('admin.reports');

    // Rute untuk download laporan
    Route::get('/reports/download/{type}', function ($type) {
        if ($type === 'procurement') {
            $tenders = \App\Models\Tender::all();
            $fileName = 'rekapitulasi_pengadaan_' . date('Ymd_His') . '.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
            ];

            $callback = function () use ($tenders) {
                $file = fopen('php://output', 'w');
                // Header CSV
                fputcsv($file, ['ID', 'Nama Paket', 'Deskripsi', 'Status', 'Tanggal Dibuat']);

                // Isi Data CSV
                foreach ($tenders as $tender) {
                    fputcsv($file, [
                        $tender->id,
                        $tender->title,
                        $tender->description,
                        $tender->status,
                        $tender->created_at ? $tender->created_at->format('Y-m-d H:i') : ''
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($type === 'vendor') {
            $vendors = \App\Models\Vendor::all();
            $fileName = 'laporan_vendor_' . date('Ymd_His') . '.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
            ];

            $callback = function () use ($vendors) {
                $file = fopen('php://output', 'w');
                // Header CSV
                fputcsv($file, ['ID', 'Nama Vendor', 'Nama Perusahaan', 'Alamat', 'Status Verifikasi']);

                // Isi Data CSV
                foreach ($vendors as $vendor) {
                    fputcsv($file, [
                        $vendor->id,
                        $vendor->name,
                        $vendor->company_name,
                        $vendor->address,
                        $vendor->status ?? 'Draft'
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Jenis laporan tidak valid.');
    })->name('admin.reports.download');

    // Rute untuk menampilkan halaman
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');

    // Rute untuk memproses form (POST)
    // Cukup tambahkan kata /admin/ di depan url settings/update
    Route::post('/admin/settings/update', function (Illuminate\Http\Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password = Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui!');
    })->name('admin.settings.update');


    // Kontak Admin Route
    // 1. RUTE FORM & PENGIRIMAN PESAN SUPPORT (KONTEN AMAN)
    Route::get('/kontak-admin', function () {
        return view('auth.contact');
    })->name('contact.admin');

    // Route::post('/kontak-admin/kirim', function (Illuminate\Http\Request $request) {
    //     // Menyimpan pengaduan ke dalam tabel notifications dengan aman menggunakan Query Builder
    //     if (Schema::hasTable('notifications')) {
    //         DB::table('notifications')->insert([
    //             'title' => 'Tiket Pengaduan Support Baru',
    //             'message' => 'Pengirim: ' . $request->name . ' (' . $request->email . ') - Pesan: ' . $request->message,
    //             'is_read' => false,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }

    //     return redirect('/')->with('success', 'Pesan pengaduan Anda berhasil terkirim ke Admin Proculus!');
    //  })->name('contact.admin.send');

    Route::get('/customer-service', [App\Http\Controllers\Admin\AdminCustomerServiceController::class, 'index'])->name('admin.customer-service');
    Route::post('/customer-service/{id}/resolve', [App\Http\Controllers\Admin\AdminCustomerServiceController::class, 'resolve'])->name('admin.customer-service.resolve');
    Route::get('/customer-service/{vendor_id}/history', [App\Http\Controllers\Admin\AdminCustomerServiceController::class, 'history'])->name('admin.cs.history');    
    Route::post('/customer-service/send', [App\Http\Controllers\Admin\AdminCustomerServiceController::class, 'send'])->name('admin.cs.send');
});
// Kontak Admin Route
// 1. RUTE FORM & PENGIRIMAN PESAN SUPPORT (KONTEN AMAN)
Route::get('/kontak-admin', function () {
    return view('auth.contact');
})->name('contact.admin');


// Rute untuk form pengaduan di Landing Page
Route::post('/contact-submit', [App\Http\Controllers\HomeController::class, 'storeContact'])->name('contact.submit');
