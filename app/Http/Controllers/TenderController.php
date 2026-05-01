<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenders;

class TenderController extends Controller
{
    public function index()
    {
       return response()->json(Tenders::all());
    }

    public function show($id)
    {
        $tenders = Tenders::with('creator')->findOrFail($id);
        return response()->json($tenders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'deadline' => 'nullable|date',
            'published_at' => 'nullable|date',
            'started_at' => 'nullable|date',
            'closed_at' => 'nullable|date',
            'status' => 'required|in:draft,published,closed',
            'created_by' => 'required|exists:admin,id',
        ]);

        $tenders = Tenders::create($validated);

        return response()->json([
            'message' => 'Tender created successfully',
            'data' => $tenders
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $tenders = Tenders::findOrFail($id);
        $validated = $request->validate([
        'title'        => 'sometimes|required|string|max:255',
        'description'  => 'nullable|string',
        'budget'       => 'nullable|numeric',
        'deadline'     => 'nullable|date',
        'published_at' => 'nullable|date',
        'started_at'   => 'nullable|date',
        'closed_at'    => 'nullable|date',
        'status'       => 'sometimes|required|in:draft,published,closed',
        'created_by'   => 'sometimes|required|exists:admin,id',
    ]);
        $tenders->update($validated);
        return response()->json([
        'message' => 'Tender updated successfully',
        'data'    => $tenders
        ]);
    }

    public function destroy($id)
    {
        $tenders = Tenders::findOrFail($id);
        $tenders->delete();
        return response()->json(['message' => 'Tendor berhasil dihapus'], 200);
    }
}
