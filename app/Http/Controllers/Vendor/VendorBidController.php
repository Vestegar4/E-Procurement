<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Bid;
use App\Models\Tender;
use Illuminate\Http\Request;
use App\Models\TenderParticipant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class VendorBidController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WEB METHODS (Blade)
    |--------------------------------------------------------------------------
    */

    // halaman daftar penawaran vendor
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(404, 'Vendor not found');
        }

        $bids = Bid::with([
            'tender',
            'tender.timeline',
            'tender.result'
        ])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view('vendor.bids', compact('bids'));
    }

    // halaman form submit bid
    public function create($tenderId)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(404, 'Vendor not found');
        }

        $tender = Tender::with('timeline')
            ->findOrFail($tenderId);

        // cek apakah vendor sudah join tender
        $participant = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if (!$participant) {
            return redirect()
                ->route('vendor.tenders.show', $tender->id)
                ->with('error', 'Anda belum bergabung pada tender ini.');
        }

        // cek apakah sudah pernah submit bid
        $existingBid = Bid::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->first();

        return view('vendor.submit-bid', compact(
            'tender',
            'existingBid'
        ));
    }

    // submit/update bid dari blade
    public function store(Request $request, $tenderId)
    {
        $request->validate([
            'bid_amount' => 'required|numeric|min:0',
            'proposal_file' => 'nullable|file|mimes:pdf|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            return back()->with('error', 'Vendor tidak ditemukan.');
        }

        $tender = Tender::with('timeline')
            ->findOrFail($tenderId);

        // cek status vendor
        if ($vendor->status !== 'approved') {
            return back()->with(
                'error',
                'Akun vendor Anda belum disetujui admin.'
            );
        }

        // cek participant
        $participant = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if (!$participant) {
            return back()->with(
                'error',
                'Anda belum bergabung pada tender ini.'
            );
        }

        // cek status tender
        if ($tender->status !== 'bidding') {
            return back()->with(
                'error',
                'Tender belum memasuki tahap bidding.'
            );
        }

        // cek periode bidding
        $now = now();

        if (
            $now->lt($tender->timeline->bidding_start) ||
            $now->gt($tender->timeline->bidding_end)
        ) {
            return back()->with(
                'error',
                'Periode bidding sudah ditutup.'
            );
        }

        // cek existing bid
        $existingBid = Bid::where([
            'vendor_id' => $vendor->id,
            'tender_id' => $tender->id
        ])->first();

        DB::beginTransaction();

        try {

            // upload file baru jika ada
            $proposalPath = $existingBid?->bid_document;

            if ($request->hasFile('proposal_file')) {

                // hapus file lama
                if ($existingBid && $existingBid->bid_document) {
                    Storage::disk('public')
                        ->delete($existingBid->bid_document);
                }

                $proposalPath = $request
                    ->file('proposal_file')
                    ->store('bids', 'public');
            }

            // update bid
            if ($existingBid) {

                $existingBid->update([
                    'bid_amount' => $request->bid_amount,
                    'notes' => $request->notes,
                    'submitted_at' => now(),
                    'bid_document' => $proposalPath,
                ]);

                DB::commit();

                return redirect()
                    ->route('vendor.bids.index')
                    ->with(
                        'success',
                        'Penawaran berhasil diperbarui.'
                    );
            }

            // validasi file wajib saat create baru
            if (!$request->hasFile('proposal_file')) {
                return back()
                    ->withErrors([
                        'proposal_file' => 'File proposal wajib diupload.'
                    ])
                    ->withInput();
            }

            // create bid baru
            Bid::create([
                'tender_id' => $tender->id,
                'vendor_id' => $vendor->id,
                'bid_amount' => $request->bid_amount,
                'notes' => $request->notes,
                'submitted_at' => now(),
                'bid_document' => $proposalPath,
                'status' => 'pending'
            ]);

            DB::commit();

            return redirect()
                ->route('vendor.bids.index')
                ->with(
                    'success',
                    'Penawaran berhasil dikirim.'
                );
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat mengirim penawaran.'
                )
                ->withInput();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | API METHODS (Mobile / Ionic)
    |--------------------------------------------------------------------------
    */

    // submit bid API
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

        if ($tender->status !== 'bidding') {
            return response()->json([
                'message' => 'Tender is not open for bidding'
            ], 400);
        }
    
        // if ($vendor->status !== 'approved') {
        //     return response()->json([
        //         'message' => 'Your vendor account is not approved'
        //     ], 403);
        // }

        if ($vendor->status !== 'approved') {
        return response()->json([
            'message' => 'Akun vendor belum approved untuk melakukan bidding.'
        ], 403);
        }
        
        $participant = TenderParticipant::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->exists();

        if (!$participant) {
            return response()->json([
                'message' => 'You are not a participant in this tender'
            ], 403);
        }

        $now = now();

        if (
            $now->lt($tender->timeline->bidding_start) ||
            $now->gt($tender->timeline->bidding_end)
        ) {
            return response()->json([
                'message' => 'Bidding period is closed'
            ], 403);
        }

        $existingBid = Bid::where([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id
        ])->first();

        // update existing bid
        if ($existingBid) {

            if ($existingBid->bid_document) {
                Storage::disk('public')
                    ->delete($existingBid->bid_document);
            }

            $bidDocumentPath = $request
                ->file('bid_document')
                ->store('bids', 'public');

            DB::transaction(function () use (
                $existingBid,
                $request,
                $bidDocumentPath
            ) {
                $existingBid->update([
                    'bid_amount' => $request->bid_amount,
                    'notes' => $request->notes,
                    'submitted_at' => now(),
                    'bid_document' => $bidDocumentPath,
                ]);
            });

            return response()->json([
                'message' => 'Bid updated successfully',
                'data' => $existingBid->fresh()
            ]);
        }

        // create new bid
        $bidDocumentPath = $request
            ->file('bid_document')
            ->store('bids', 'public');

        $bid = Bid::create([
            'tender_id' => $tender->id,
            'vendor_id' => $vendor->id,
            'bid_amount' => $request->bid_amount,
            'notes' => $request->notes,
            'submitted_at' => now(),
            'bid_document' => $bidDocumentPath,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Bid submitted successfully',
            'data' => $bid
        ], 201);
    }

    // list my bids API
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

    // detail bid API
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
