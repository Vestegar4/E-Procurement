<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tender;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TenderTimeline;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TenderController extends Controller
{
    // list tender    
    public function index()
    {
        $tenders = Tender::with('timeline')
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Tenders retrieved successfully',
            'data' => $tenders
        ]);
    }

    // create tender
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specification' => 'required|string',
            'budget' => 'required|numeric',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'aanwijzing_at' => 'required|date',
            'bidding_start' => 'required|date',
            'bidding_end' => 'required|date|after:bidding_start',
            'document_base64' => 'nullable|string', #validasi string teks base64
        ]);

        $user = auth()->user();
        $admin = $user->admin;

        #proses konversi base64 ke file pdf
        $documentPath = null;
        if ($request->filled('document_base64')) {
            $base64String = $request->document_base64;

            if (strpos($base64String, ',') !== false) {
                @list($type, $base64String) = explode(',', $base64String);
            }
            // Decode string menjadi biner file asli
            $fileData = base64_decode($base64String);
            // Buat nama file acak yang unik agar tidak tumpang tindih
            $fileName = 'tenders/documents/tender_doc_' . Str::random(10) . '_' . time() . '.pdf';
            // Simpan ke folder storage/app/public/tenders/documents
            Storage::disk('public')->put($fileName, $fileData);

            $documentPath = $fileName;
        }

        $tender = Tender::create([
            'title' => $request->title,
            'description' => $request->description,
            'specification' => $request->specification,
            'budget' => $request->budget,
            'document_path' => $documentPath,
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        TenderTimeline::create([
            'tender_id' => $tender->id,
            'registration_start' => $request->registration_start,
            'registration_end' => $request->registration_end,
            'aanwijzing_at' => $request->aanwijzing_at,
            'bidding_start' => $request->bidding_start,
            'bidding_end' => $request->bidding_end,
        ]);

        return response()->json([
            'message' => 'Tender created successfully',
            'data' => $tender->load('timeline')
        ], 201);
    }

    // create tender plan from admin web form
    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specification' => 'required|string',
            'budget' => 'required|numeric',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'aanwijzing_at' => 'required|date',
            'bidding_start' => 'required|date',
            'bidding_end' => 'required|date|after:bidding_start',
        ]);

        $user = auth()->user();
        $admin = $user->admin;

        $tender = Tender::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'specification' => $validated['specification'],
            'budget' => $validated['budget'],
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        TenderTimeline::create([
            'tender_id' => $tender->id,
            'registration_start' => $validated['registration_start'],
            'registration_end' => $validated['registration_end'],
            'aanwijzing_at' => $validated['aanwijzing_at'],
            'bidding_start' => $validated['bidding_start'],
            'bidding_end' => $validated['bidding_end'],
        ]);

        return back()->with('success', 'Tender berhasil dibuat.');
    }

    public function publish(Request $request, Tender $tender)
    {
        if ($tender->status !== 'draft') {
            return back()->with('success', 'Tender sudah dipublish.');
        }

        if (!$tender->timeline) {
            return back()->withErrors(['timeline' => 'Timeline tender belum diisi.']);
        }

        $tender->update([
            'status' => 'open',
        ]);

        return back()->with('success', 'Tender berhasil dipublish.');
    }

    public function updateWeb(Request $request, Tender $tender)
    {
        $user = auth()->user();
        $admin = $user->admin;

        if ($tender->created_by !== $admin->id) {
            return back()->withErrors(['auth' => 'Anda tidak berhak mengubah tender ini.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'specification' => 'required|string',
            'budget' => 'required|numeric',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start',
            'aanwijzing_at' => 'required|date',
            'bidding_start' => 'required|date',
            'bidding_end' => 'required|date|after:bidding_start',
        ]);

        $tender->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'specification' => $validated['specification'],
            'budget' => $validated['budget'],
        ]);

        if ($tender->timeline) {
            $tender->timeline->update([
                'registration_start' => $validated['registration_start'],
                'registration_end' => $validated['registration_end'],
                'aanwijzing_at' => $validated['aanwijzing_at'],
                'bidding_start' => $validated['bidding_start'],
                'bidding_end' => $validated['bidding_end'],
            ]);
        } else {
            TenderTimeline::create([
                'tender_id' => $tender->id,
                'registration_start' => $validated['registration_start'],
                'registration_end' => $validated['registration_end'],
                'aanwijzing_at' => $validated['aanwijzing_at'],
                'bidding_start' => $validated['bidding_start'],
                'bidding_end' => $validated['bidding_end'],
            ]);
        }

        return back()->with('success', 'Tender berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Tender $tender)
    {
        $user = auth()->user();
        $admin = $user->admin;

        if ($tender->created_by !== $admin->id) {
            return back()->withErrors(['auth' => 'Anda tidak berhak mengubah status tender ini.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,open,aanwijzing,bidding,closed,finished',
        ]);

        $tender->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status tender berhasil diubah.');
    }

    // show tender
    public function show($id)
    {
        $tender = Tender::with([
            'creator',
            'timeline',
            'participants',
            'announcements',
            'bids'
        ])->findOrFail($id);

        return response()->json([
            'message' => 'Tender detail retrieved successfully',
            'data' => $tender
        ]);
    }

    // update tender
    public function update(Request $request, $id)
    {
        $tender = Tender::with('timeline')
            ->findOrFail($id);

        $user = auth()->user();
        $admin = $user->admin;

        // Authorize: only creator can update
        if ($tender->created_by !== $admin->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'specification' => 'sometimes|required|string',
            'budget' => 'sometimes|required|numeric',
            'registration_start' => 'sometimes|required|date',
            'registration_end' => 'sometimes|required|date|after:registration_start',
            'bidding_end' => 'sometimes|required|date|after:bidding_start',
            'document_base64' => 'nullable|string',
        ]);

        #proses konversi base64 ke file pdf
        if ($request->filled('document_base64')) {
            $base64String = $request->document_base64;

            if (strpos($base64String, ',') !== false) {
                @list($type, $base64String) = explode(',', $base64String);
            }
            // Decode string menjadi biner file asli
            $fileData = base64_decode($base64String);
            // Buat nama file acak yang unik agar tidak tumpang tindih
            $fileName = 'tenders/documents/tender_doc_' . Str::random(10) . '_' . time() . '.pdf';
            // Simpan ke folder storage/app/public/tenders/documents
            Storage::disk('public')->put($fileName, $fileData);

            // Hapus file lama jika ada
            if ($tender->document_path) {
                Storage::disk('public')->delete($tender->document_path);
            }
            $tender->document_path = $fileName;
        }

        $tender->update($request->only([
            'title',
            'description',
            'specification',
            'budget',
            'document_path',
            'status'
        ]));

        if ($tender->timeline) {
            $tender->timeline->update($request->only([
                'registration_start',
                'registration_end',
                'aanwijzing_at',
                'bidding_start',
                'bidding_end',
            ]));
        }

        return response()->json([
            'message' => 'Tender updated successfully',
            'data' => $tender->fresh()->load('timeline')
        ]);
    }

    // delete tender
    public function destroy($id)
    {
        $tender = Tender::findOrFail($id);

        $user = auth()->user();
        $admin = $user->admin;

        // Authorize: only creator can delete
        if ($tender->created_by !== $admin->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($tender->document_path) {
            Storage::disk('public')->delete($tender->document_path);
        }

        $tender->delete();

        return response()->json([
            'message' => 'Tender deleted successfully'
        ]);
    }
}
