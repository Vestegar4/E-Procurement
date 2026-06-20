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

        $vendor = auth()->user() ? auth()->user()->vendor : null;
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

        // ▼ PERBAIKAN: Selalu gunakan JSON jika ini dari rute API ▼
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Tenders retrieved successfully',
                'data' => $tenders->items(),
                'joined_tender_ids' => $joinedTenderIds
            ]);
        }

        return view('vendor.tenders', compact('tenders', 'status', 'search', 'joinedTenderIds'));
    }

    // detail tender
    public function show(Request $request, $id)
    {
        $tender = Tender::with([
            'timeline',
            'announcements',
            'participants',
            'result'
        ])->findOrFail($id);

        $vendor = auth()->user() ? auth()->user()->vendor : null;
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
        
        // ▼ PERBAIKAN: Selalu gunakan JSON jika ini dari rute API ▼
        if ($request->expectsJson() || $request->is('api/*')) {
            $tender->effective_status = $effectiveStatus;
            return response()->json([
                'message' => 'Tender detail retrieved successfully',
                'data' => [
                    'tender' => $tender,
                    'is_joined' => $isJoined,
                    'is_winner' => $isWinner,
                    'is_loser' => $isLoser
                ]
            ]);
        }
        
        return view('vendor.tender-detail', compact('tender', 'effectiveStatus', 'isJoined', 'isWinner', 'isLoser'));
    }

    // vendor join tender
    public function join(Request $request, $id)
    {
        $vendor = auth()->user()->vendor;
        $isApi = $request->expectsJson() || $request->is('api/*');

        if (!$vendor) {
            return $isApi 
                ? response()->json(['message' => 'Profil vendor tidak ditemukan.'], 404)
                : back()->withErrors(['join' => 'Profil vendor tidak ditemukan.']);
        }

        if ($vendor->status !== 'approved') {
            return $isApi
                ? response()->json(['message' => 'Akun vendor masih menunggu approval admin.'], 403)
                : back()->withErrors(['join' => 'Akun vendor masih menunggu approval admin.']);
        }

        $tender = Tender::with('timeline')->findOrFail($id);
        $now = now();

        if (!$tender->timeline) {
            return $isApi
                ? response()->json(['message' => 'Timeline tender belum tersedia.'], 400)
                : back()->withErrors(['join' => 'Timeline tender belum tersedia.']);
        }

        if ($tender->timeline->registration_start && $tender->timeline->registration_end) {
            if ($now < $tender->timeline->registration_start || $now > $tender->timeline->registration_end) {
                return $isApi
                    ? response()->json(['message' => 'Periode registrasi sudah ditutup.'], 400)
                    : back()->withErrors(['join' => 'Periode registrasi sudah ditutup.']);
            }
        }

        $alreadyJoined = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if ($alreadyJoined) {
            return $isApi
                ? response()->json(['message' => 'Anda sudah join tender ini.'], 400)
                : back()->withErrors(['join' => 'Anda sudah join tender ini.']);
        }

        TenderParticipant::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id,
            'joined_at' => now(),
        ]);

        return $isApi
            ? response()->json(['message' => 'Berhasil join tender.'], 200)
            : back()->with('success', 'Berhasil join tender.');
    }

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
            'data' => $joined->items()
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

        // 1. Cek Masa Registrasi (HANYA dieksekusi JIKA datanya tidak kosong)
        if ($timeline->registration_start && $timeline->registration_end) {
            if ($now->lt($timeline->registration_start)) {
                return $tender->status ?? 'draft';
            }
            if ($now->between($timeline->registration_start, $timeline->registration_end)) {
                return 'open';
            }
        }

        // 2. Cek Masa Aanwijzing (HANYA dieksekusi JIKA datanya tidak kosong)
        if ($timeline->aanwijzing_at && $now->lt($timeline->aanwijzing_at)) {
            return 'aanwijzing';
        }

        // 3. Cek Masa Bidding
        if ($timeline->bidding_start && $timeline->bidding_end) {
            if ($now->between($timeline->bidding_start, $timeline->bidding_end)) {
                return 'bidding';
            }
            if ($now->gt($timeline->bidding_end)) {
                return 'closed';
            }
        }

        return $tender->status ?? 'open';
    }
}