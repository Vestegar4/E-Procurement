<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\PurchaseOrder; // Pastikan ini di-import
use Illuminate\Http\Request;
use App\Models\TenderResult;
use App\Http\Controllers\Controller;

class TenderResultController extends Controller
{
    // select winner
    public function selectWinner(Request $request, $tenderId)
    {
        $request->validate([
            'bid_id' => 'required|exists:bids,id',
            'notes' => 'nullable|string',
        ]);

        $tender = Tender::findOrFail($tenderId);
        $user = auth()->user();
        $admin = $user->admin;

        // 1. Pastikan belum ada pemenang yang dipilih untuk tender ini
        $existingResult = TenderResult::where('tender_id', $tender->id)->exists();

        if ($existingResult) {
            return response()->json([
                'message' => 'Winner already selected'
            ], 409);
        }

        // 2. Pastikan bid yang dipilih benar-benar terkait dengan tender ini
        $bid = Bid::where('tender_id', $tender->id)
            ->findOrFail($request->bid_id);

        // 3. Simpan hasil penetapan pemenang (Menggunakan struktur kolom baru)
        $result = TenderResult::create([
            'tender_id'        => $tender->id,
            'winner_vendor_id' => $bid->vendor_id,
            'winning_bid'      => $bid->bid_amount,
            'notes'            => $request->notes,
            'selected_by'      => $admin->id,
            'selected_at'      => now(),
        ]);

        // 4. Update status tender
        $tender->update([
            'status' => 'finished' 
        ]);

        // 5. OTOMATIS BUAT PURCHASE ORDER (PO) 🚀
        PurchaseOrder::create([
            'tender_id' => $tender->id,
            'vendor_id' => $bid->vendor_id,
            'po_number' => 'PO-' . date('Ymd') . '-' . $tender->id,
            'total_amount' => $bid->bid_amount,
            'status'    => 'draft', 
        ]);

        return response()->json([
            'message' => 'Winner selected and Purchase Order created successfully!',
            'data'    => $result
        ], 201);
    }

    // show tender result
    public function show($tenderId)
    {
        $result = TenderResult::with([
            'winner',
            'selector',
            'tender'
        ])
            ->where('tender_id', $tenderId)
            ->firstOrFail();

        return response()->json([
            'message' => 'Tender result retrieved successfully',
            'data' => $result
        ]);
    }
}