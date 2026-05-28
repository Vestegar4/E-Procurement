<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;
use App\Models\Bid;
use App\Models\TenderResult;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCloseTender extends Command
{
    // Nama perintah yang akan diketik di terminal
    protected $signature = 'tender:auto-close';

    // Deskripsi tugas
    protected $description = 'Menutup tender yang melewati batas waktu bidding dan memilih pemenang secara otomatis';

    public function handle()
    {
        // 1. Cari semua tender yang statusnya masih 'bidding' tapi waktu bidding_end sudah lewat
        $tenders = Tender::where('status', 'bidding')
            ->whereHas('timeline', function ($query) {
                $query->where('bidding_end', '<=', Carbon::now());
            })
            ->with('timeline')
            ->get();

        $count = 0;

        foreach ($tenders as $tender) {
            // 2. Ubah status tender menjadi closed
            $tender->update(['status' => 'closed']);

            // 3. Cari penawaran (bidding) yang masuk untuk tender ini
            $bids = Bid::where('tender_id', $tender->id)->get();

            if ($bids->count() > 0) {
                // LOGIKA PENENTUAN PEMENANG DASAR (Harga Terendah)
                // ------------------------------------------------
                // Catatan Pengembangan: Untuk tingkat proyek akhir, blok kode penentuan pemenang di bawah ini 
                // adalah titik yang paling ideal jika Anda ingin menyematkan model algoritma klasifikasi 
                // seperti K-Nearest Neighbors (KNN). Anda bisa mengganti logika harga terendah ini dengan 
                // kalkulasi skor KNN untuk menilai vendor berdasarkan multi-kriteria.
                
                $winningBid = $bids->sortBy('bid_amount')->first();

                // 4. Catat ke tabel TenderResult
                TenderResult::create([
                    'tender_id' => $tender->id,
                    'winner_vendor_id' => $winningBid->vendor_id,
                    'winning_bid' => $winningBid->bid_amount,
                    'notes' => 'Pemenang dipilih secara otomatis oleh sistem (Harga Terendah).',
                    'selected_at' => Carbon::now(),
                ]);

                // 5. Terbitkan PO (Purchase Order) berstatus Draft
                PurchaseOrder::create([
                    'tender_id' => $tender->id,
                    'vendor_id' => $winningBid->vendor_id,
                    'po_number' => 'PO-AUTO-' . date('Ymd') . '-' . $tender->id,
                    'total_amount' => $winningBid->bid_amount,
                    'status' => 'draft'
                ]);

                Log::info("Tender ID {$tender->id} otomatis ditutup. Pemenang: Vendor ID {$winningBid->vendor_id}.");
            } else {
                // Jika tidak ada yang menawar sama sekali
                $tender->update(['status' => 'finished']); // Atau status 'failed' sesuai aturan Anda
                Log::info("Tender ID {$tender->id} otomatis ditutup tanpa ada peserta bidding.");
            }

            $count++;
        }

        $this->info("Berhasil menutup {$count} tender yang sudah kedaluwarsa.");
    }
}