<?php

namespace App\Http\Controllers\Vendor;

use App\Models\TenderResult;
use App\Http\Controllers\Controller;
use App\Models\User;

class VendorResultController extends Controller
{

    // list my results
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json([
                'message' => 'Profil Vendor tidak ditemukan.'
            ], 404);
        }

        $results = TenderResult::with([
            'tender',
            'winner',
            'selector'
        ])
            ->where('winner_vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Tender results retrieved successfully',
            'data' => $results
        ]);
    }

    // detail result
    public function show($tenderId)
    {
        $result = TenderResult::with([
            'tender',
            'winner',
            'selector'
        ])
            ->where('tender_id', $tenderId)
            ->firstOrFail();

        return response()->json([
            'message' => 'Tender result detail retrieved successfully',
            'data' => $result
        ]);
    }
}
