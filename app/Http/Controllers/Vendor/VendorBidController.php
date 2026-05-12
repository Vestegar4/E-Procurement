<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Tender;
use App\Models\TenderParticipant;
use Illuminate\Support\Facades\Storage;

class VendorBidController extends Controller
{
    // submit bid
    public function submitBid(Request $request, $tenderId)
    {
        $request->validate([
            'bid_amount' => 'required|numeric|min:0',
            'bid_document' => 'required|file|mimes:pdf|max:2048',
        ]);

        $user = auth()->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found'
            ], 404);
        }

        $tender = Tender::with('timeline')
            ->findOrFail($tenderId);

        // check if tender is open for bidding
        if (!in_array($tender->status, ['open', 'bidding'])) {
            return response()->json([
                'message' => 'Tender is not open for bidding'
            ], 400);
        }

        // check if vendor is a participant
        $participant = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if (!$participant) {
            return response()->json([
                'message' => 'You are not a participant in this tender'
            ], 403);
        }

        // check bidding period
        $now = now();

        if ($now->lt($tender->timeline->bidding_start) || $now->gt($tender->timeline->bidding_end)) {
            return response()->json([
                'message' => 'Bidding period is closed'
            ], 403);
        }

        // check existing bid
        $existingBid = Bid::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->first();

        // update existing bid
        if ($existingBid) {
            // delete old document
            if ($existingBid->bid_document) {
                Storage::delete($existingBid->bid_document);
            }

            // store new document
            $bidDocumentPath = $request->file('bid_document')->store('bids');

            // update bid
            $existingBid->update([
                'bid_amount' => $request->bid_amount,
                'notes' => $request->notes,
                'submitted_at' => now(),
                'bid_document' => $bidDocumentPath,
            ]);

            return response()->json([
                'message' => 'Bid updated successfully',
                'data' => $existingBid
            ]);
        }

        // store bid document
        $bidDocumentPath = $request->file('bid_document')->store('bids');

        // create new bid
        $bid = Bid::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id,
            'bid_amount' => $request->bid_amount,
            'notes' => $request->notes,
            'submitted_at' => now(),
            'bid_document' => $bidDocumentPath,
        ]);

        return response()->json([
            'message' => 'Bid submitted successfully',
            'data' => $bid
        ], 201);
    }
    // list my bids
    public function myBids()
    {
        $user = auth()->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found'
            ], 404);
        }

        $bids = Bid::with([
            'tender.timeline'
        ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Bids retrieved successfully',
            'data' => $bids
        ]);
    }

    // bid detail
    public function show($id)
    {
        $user = auth()->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found'
            ], 404);
        }

        $bid = Bid::with([
            'tender',
            'tender.timeline',
            'vendor'
        ])
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        return response()->json([
            'message' => 'Bid detail retrieved successfully',
            'data' => $bid
        ]);
    }
}
