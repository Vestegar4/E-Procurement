<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Bid;
use App\Models\Invoice;
use App\Models\TenderParticipant;
use App\Http\Controllers\Controller;

class VendorReportController extends Controller
{
  public function index()
  {
    $vendor = auth()->user()->vendor;

    if (!$vendor) {
      abort(404, 'Vendor not found');
    }

    /*
        |--------------------------------------------------------------------------
        | SUMMARY STATS
        |--------------------------------------------------------------------------
        */

    $joinedTenders = TenderParticipant::where(
      'vendor_id',
      $vendor->id
    )->count();

    $submittedBids = Bid::where(
      'vendor_id',
      $vendor->id
    )->count();

    $winningBids = Bid::where(
      'vendor_id',
      $vendor->id
    )
      ->where('status', 'won')
      ->count();

    $totalBidAmount = Bid::where(
      'vendor_id',
      $vendor->id
    )->sum('bid_amount');

    /*
        |--------------------------------------------------------------------------
        | RECENT BIDS
        |--------------------------------------------------------------------------
        */

    $recentBids = Bid::with('tender')
      ->where('vendor_id', $vendor->id)
      ->latest()
      ->take(5)
      ->get();

    /*
        |--------------------------------------------------------------------------
        | JOINED TENDERS
        |--------------------------------------------------------------------------
        */

    $recentTenders = TenderParticipant::with(
      'tender'
    )
      ->where('vendor_id', $vendor->id)
      ->latest()
      ->take(5)
      ->get();

    /*
        |--------------------------------------------------------------------------
        | INVOICES
        |--------------------------------------------------------------------------
        */

    $invoices = Invoice::where(
      'vendor_id',
      $vendor->id
    )
      ->latest()
      ->take(5)
      ->get();

    return view('vendor.reports', compact(
      'joinedTenders',
      'submittedBids',
      'winningBids',
      'totalBidAmount',
      'recentBids',
      'recentTenders',
      'invoices'
    ));
  }
}
