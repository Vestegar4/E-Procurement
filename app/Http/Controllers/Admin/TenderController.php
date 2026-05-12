<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tender;
use Illuminate\Http\Request;
use App\Models\TenderTimeline;
use App\Http\Controllers\Controller;

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
        ]);

        $user = auth()->user();
        $admin = $user->admin;

        $tender = Tender::create([
            'title' => $request->title,

            'description' => $request->description,

            'specification' => $request->specification,

            'budget' => $request->budget,

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

        $tender->update($request->only([
            'title',
            'description',
            'specification',
            'budget',
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

        $tender->delete();

        return response()->json([
            'message' => 'Tender deleted successfully'
        ]);
    }
}
