<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Tender;
use Illuminate\Http\Request;
use App\Models\TenderParticipant;
use App\Http\Controllers\Controller;

class VendorTenderController extends Controller
{
    // list tenders that are open for bidding
    public function index(Request $request)
    {
        $allowedStatuses = ['open', 'aanwijzing', 'bidding', 'closed', 'finished'];
        $status = $request->query('status');
        $search = $request->query('q');

        $tenders = Tender::with(['timeline', 'result'])
            ->when($status && in_array($status, $allowedStatuses, true), function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->whereIn('status', ['open', 'aanwijzing', 'bidding']);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $vendor = auth()->user()->vendor;
        $joinedTenderIds = $vendor
            ? TenderParticipant::where('vendor_id', $vendor->id)->pluck('tender_id')->all()
            : [];

        $tenders->getCollection()->transform(function ($tender) use ($vendor, $joinedTenderIds) {
            $tender->effective_status = $this->resolveTenderStatus($tender);
            $isJoined = $vendor && in_array($tender->id, $joinedTenderIds, true);
            $tender->is_winner = $isJoined && $tender->result
                ? $tender->result->winner_vendor_id === $vendor->id
                : false;
            $tender->is_loser = $isJoined && $tender->result
                ? $tender->result->winner_vendor_id !== $vendor->id
                : false;
            return $tender;
        });

        return view('vendor.tenders', compact('tenders', 'status', 'search', 'joinedTenderIds'));
    }

    // detail tender
    public function show($id)
    {
        $tender = Tender::with([
            'timeline',
            'announcements',
            'participants',
            'result'
        ])->findOrFail($id);

        $vendor = auth()->user()->vendor;
        $isJoined = $vendor
            ? TenderParticipant::where('tender_id', $tender->id)
            ->where('vendor_id', $vendor->id)
            ->exists()
            : false;

        $effectiveStatus = $this->resolveTenderStatus($tender);

        $isWinner = $vendor && $tender->result
            ? $tender->result->winner_vendor_id === $vendor->id
            : false;

        $isLoser = $vendor && $tender->result && $isJoined
            ? $tender->result->winner_vendor_id !== $vendor->id
            : false;

        return view('vendor.tender-detail', compact('tender', 'effectiveStatus', 'isJoined', 'isWinner', 'isLoser'));
    }

    // vendor join tender
    public function join($id)
    {
        $user = auth()->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return back()->withErrors(['join' => 'Profil vendor tidak ditemukan.']);
        }
        $tender = Tender::with('timeline')
            ->findOrFail($id);

        // pastikan vendor sudah diapprove admin
        if ($vendor->status !== 'approved') {
            return back()->withErrors(['join' => 'Vendor belum disetujui admin.']);
        }

        // gunakan waktu server untuk validasi, agar tidak bisa dimanipulasi dari client
        $now = now();

        if (!$tender->timeline) {
            return back()->withErrors(['join' => 'Timeline tender belum tersedia.']);
        }

        if (
            $now < $tender->timeline->registration_start ||
            $now > $tender->timeline->registration_end
        ) {
            return back()->withErrors(['join' => 'Periode registrasi sudah ditutup.']);
        }

        // cek apakah vendor sudah pernah join tender ini
        $alreadyJoined = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if ($alreadyJoined) {
            return back()->withErrors(['join' => 'Anda sudah join tender ini.']);
        }

        // buat entry di tabel peserta tender
        TenderParticipant::create([
            'tender_id' => $tender->id,

            'vendor_id' => $vendor->id,

            'joined_at' => now(),
        ]);

        return back()->with('success', 'Berhasil join tender.');
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

    protected function resolveTenderStatus(Tender $tender)
    {
        if (in_array($tender->status, ['finished', 'closed'], true)) {
            return $tender->status;
        }

        $timeline = $tender->timeline;
        if (!$timeline) {
            return $tender->status ?? 'draft';
        }

        $now = now();

        if ($now->lt($timeline->registration_start)) {
            return $tender->status ?? 'draft';
        }

        if ($now->between($timeline->registration_start, $timeline->registration_end)) {
            return 'open';
        }

        if ($now->lt($timeline->aanwijzing_at)) {
            return 'aanwijzing';
        }

        if ($now->between($timeline->bidding_start, $timeline->bidding_end)) {
            return 'bidding';
        }

        if ($now->gt($timeline->bidding_end)) {
            return 'closed';
        }

        return $tender->status ?? 'draft';
    }
}
