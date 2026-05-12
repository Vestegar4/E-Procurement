<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bid;
use App\Models\Tender;
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

        // pastikan belum ada pemenang yang dipilih untuk tender ini
        $existingResult = TenderResult::where(
            'tender_id',
            $tender->id
        )->exists();

        if ($existingResult) {
            return response()->json([
                'message' => 'Winner already selected'
            ], 409);
        }


        // pastikan bid yang dipilih benar-benar terkait dengan tender ini
        $bid = Bid::where('tender_id', $tender->id)
            ->findOrFail($request->bid_id);


        // create result
        $result = TenderResult::create([
            'tender_id' => $tender->id,

            'winner_vendor_id' => $bid->vendor_id,

            'winning_bid' => $bid->bid_amount,

            'notes' => $request->notes,

            'selected_by' => $admin->id,

            'selected_at' => now(),
        ]);

        // update status tender
        $tender->update([
            'status' => 'finished'
        ]);

        return response()->json([
            'message' => 'Winner selected successfully',
            'data' => $result
        ]);
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
