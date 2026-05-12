<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TenderAnnouncement;
use App\Models\Tender;

class TenderAnnouncementController extends Controller
{
  // create announcement
  public function store(Request $request, $tenderId)
  {
    $request->validate([
      'title' => 'required|string|max:255',

      'message' => 'required|string',
    ]);

    $tender = Tender::findOrFail($tenderId);

        $user = auth()->user();
        $admin = $user->admin;

        $announcement = TenderAnnouncement::create([
            'tender_id' => $tender->id,

            'title' => $request->title,

            'message' => $request->message,

            'created_by' => $admin->id,
        ]);

    $announcements = TenderAnnouncement::with('creator')
      ->where('tender_id', $tenderId)
      ->latest()
      ->paginate(15);

    return response()->json([
      'message' => 'Announcements retrieved successfully',
      'data' => $announcements
    ]);
  }
}
