<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Tender;
use Illuminate\Http\Request;
use App\Models\TenderParticipant;
use App\Http\Controllers\Controller;

class VendorTenderController extends Controller
{
    // list tenders that are open for bidding
    public function index()
    {
        $tenders = Tender::with('timeline')
            ->whereIn('status', ['open', 'bidding'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Tender list retrieved successfully',
            'data' => $tenders
        ]);
    }

    // detail tender
    public function show($id)
    {
        $tender = Tender::with([
            'timeline',
            'announcements',
        ])
            ->findOrFail($id);

        return response()->json([
            'message' => 'Tender detail retrieved successfully',
            'data' => $tender
        ]);
    }

    // vendor join tender
    public function join($id)
    {
        $user = auth()->user();
        $vendor = $user->vendor;

        $tender = Tender::with('timeline')
            ->findOrFail($id);

        // pastikan vendor sudah diapprove admin
        if ($vendor->status !== 'approved') {

            return response()->json([
                'message' => 'Vendor not approved'
            ], 403);
        }

        // gunakan waktu server untuk validasi, agar tidak bisa dimanipulasi dari client
        $now = now();

        if (
            $now < $tender->timeline->registration_start ||
            $now > $tender->timeline->registration_end
        ) {
            return response()->json([
                'message' => 'Registration period closed'
            ], 403);
        }

        // cek apakah vendor sudah pernah join tender ini
        $alreadyJoined = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if ($alreadyJoined) {

            return response()->json([
                'message' => 'Already joined this tender'
            ], 409);
        }

        // buat entry di tabel peserta tender
        TenderParticipant::create([
            'tender_id' => $tender->id,

            'vendor_id' => $vendor->id,

            'joined_at' => now(),
        ]);

        return response()->json([
            'message' => 'Successfully joined tender'
        ]);
    }

    // joined Tenders
    public function myTenders()
    {
        $user = auth()->user();
        $vendor = $user->vendor;

        $joined = TenderParticipant::with([
            'tender.timeline'
        ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Joined tenders retrieved successfully',
            'data' => $joined
        ]);
    }
}
