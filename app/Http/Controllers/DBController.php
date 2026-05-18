<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\Vendor;
use App\Models\Admin;
use App\Models\User;
use App\Models\TenderResult;
use App\Models\TenderParticipant;
use App\Models\TenderAnnouncement;
use App\Models\VendorDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBController extends Controller
{
    /**
     * Get comprehensive database statistics and system metrics
     */
    public function getSystemStats()
    {
        try {
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'admins' => User::where('role', 'admin')->count(),
                    'vendors' => User::where('role', 'vendor')->count(),
                    'active_today' => User::where('last_login_at', '>=', now()->subDay())->count(),
                ],
                'tenders' => [
                    'total' => Tender::count(),
                    'draft' => Tender::where('status', 'draft')->count(),
                    'open' => Tender::where('status', 'open')->count(),
                    'bidding' => Tender::where('status', 'bidding')->count(),
                    'finished' => Tender::where('status', 'finished')->count(),
                ],
                'vendors' => [
                    'total' => Vendor::count(),
                    'approved' => Vendor::where('status', 'approved')->count(),
                    'pending' => Vendor::where('status', 'pending')->count(),
                    'rejected' => Vendor::where('status', 'rejected')->count(),
                ],
                'bids' => [
                    'total' => Bid::count(),
                    'this_month' => Bid::whereMonth('created_at', now()->month)->count(),
                    'this_year' => Bid::whereYear('created_at', now()->year)->count(),
                ],
                'results' => [
                    'total_results' => TenderResult::count(),
                    'completed_tenders' => Tender::has('result')->count(),
                ],
                'system' => [
                    'timestamp' => now()->toIso8601String(),
                    'database_connection' => config('database.default'),
                ]
            ];

            return response()->json([
                'message' => 'System statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving system statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed tender analytics
     */
    public function getTenderAnalytics()
    {
        try {
            $totalBudget = Tender::sum('budget');
            $averageBudget = Tender::avg('budget');

            $tendersByStatus = Tender::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            $topTenders = Tender::with(['creator', 'participants'])
                ->withCount('bids')
                ->orderByDesc('budget')
                ->limit(10)
                ->get()
                ->map(function ($tender) {
                    return [
                        'id' => $tender->id,
                        'title' => $tender->title,
                        'budget' => $tender->budget,
                        'status' => $tender->status,
                        'bids_count' => $tender->bids_count,
                        'participants_count' => $tender->participants_count,
                        'created_by' => $tender->creator?->name,
                    ];
                });

            $analytics = [
                'total_tenders' => Tender::count(),
                'total_budget' => $totalBudget,
                'average_budget' => round($averageBudget, 2),
                'by_status' => $tendersByStatus,
                'top_tenders' => $topTenders,
            ];

            return response()->json([
                'message' => 'Tender analytics retrieved successfully',
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving tender analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed vendor analytics
     */
    public function getVendorAnalytics()
    {
        try {
            $vendorsByStatus = Vendor::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            $topVendors = Vendor::with(['user'])
                ->withCount(['bids', 'participants', 'winningResults'])
                ->orderByDesc('bids_count')
                ->limit(10)
                ->get()
                ->map(function ($vendor) {
                    return [
                        'id' => $vendor->id,
                        'company_name' => $vendor->company_name,
                        'email' => $vendor->user?->email,
                        'status' => $vendor->status,
                        'bids_count' => $vendor->bids_count,
                        'tenders_joined' => $vendor->participants_count,
                        'won_tenders' => $vendor->winning_results_count,
                        'approved_at' => $vendor->approved_at,
                    ];
                });

            $analytics = [
                'total_vendors' => Vendor::count(),
                'by_status' => $vendorsByStatus,
                'top_vendors' => $topVendors,
            ];

            return response()->json([
                'message' => 'Vendor analytics retrieved successfully',
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving vendor analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detailed bid analytics
     */
    public function getBidAnalytics()
    {
        try {
            $totalBidsAmount = Bid::sum('bid_amount');
            $averageBidAmount = Bid::avg('bid_amount');
            $highestBid = Bid::with('tender')->orderByDesc('bid_amount')->first();
            $lowestBid = Bid::with('tender')->orderByDesc('bid_amount')->last();

            $bidsByMonth = Bid::selectRaw('MONTH(created_at) as month, count(*) as count, SUM(bid_amount) as total_amount')
                ->whereYear('created_at', now()->year)
                ->groupByRaw('MONTH(created_at)')
                ->orderBy('month')
                ->get();

            $topBidders = Vendor::with(['user'])
                ->withCount(['bids' => function ($query) {
                    $query->selectRaw('count(*) as aggregate');
                }])
                ->orderByDesc('bids_count')
                ->limit(10)
                ->get()
                ->map(function ($vendor) {
                    return [
                        'vendor_id' => $vendor->id,
                        'company_name' => $vendor->company_name,
                        'email' => $vendor->user?->email,
                        'total_bids' => $vendor->bids_count,
                    ];
                });

            $analytics = [
                'total_bids' => Bid::count(),
                'total_bid_amount' => $totalBidsAmount,
                'average_bid_amount' => round($averageBidsAmount, 2),
                'highest_bid' => $highestBid ? [
                    'amount' => $highestBid->bid_amount,
                    'tender_title' => $highestBid->tender?->title,
                    'vendor' => $highestBid->vendor?->company_name,
                ] : null,
                'lowest_bid' => $lowestBid ? [
                    'amount' => $lowestBid->bid_amount,
                    'tender_title' => $lowestBid->tender?->title,
                    'vendor' => $lowestBid->vendor?->company_name,
                ] : null,
                'bids_by_month' => $bidsByMonth,
                'top_bidders' => $topBidders,
            ];

            return response()->json([
                'message' => 'Bid analytics retrieved successfully',
                'data' => $analytics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving bid analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get financial summary and metrics
     */
    public function getFinancialSummary()
    {
        try {
            $totalTenderBudget = Tender::sum('budget');
            $totalBidsAmount = Bid::sum('bid_amount');
            $winningBidsTotal = TenderResult::sum('winning_bid');

            $budgetUtilization = $totalTenderBudget > 0
                ? round(($winningBidsTotal / $totalTenderBudget) * 100, 2)
                : 0;

            $tendersWithResults = Tender::has('result')->count();
            $tendersWithoutResults = Tender::doesntHave('result')->count();

            $financialData = [
                'total_tender_budget' => $totalTenderBudget,
                'total_bids_amount' => $totalBidsAmount,
                'total_winning_bids' => $winningBidsTotal,
                'budget_utilization_percentage' => $budgetUtilization,
                'tenders_with_results' => $tendersWithResults,
                'tenders_without_results' => $tendersWithoutResults,
                'average_winning_bid' => TenderResult::avg('winning_bid'),
            ];

            return response()->json([
                'message' => 'Financial summary retrieved successfully',
                'data' => $financialData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving financial summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent system activities
     */
    public function getRecentActivities($limit = 50)
    {
        try {
            $activities = [];

            // Recent tenders created
            $recentTenders = Tender::with('creator')
                ->latest('created_at')
                ->limit($limit)
                ->get()
                ->map(function ($tender) {
                    return [
                        'type' => 'tender_created',
                        'title' => $tender->title,
                        'actor' => $tender->creator?->name,
                        'timestamp' => $tender->created_at,
                    ];
                });

            // Recent bids submitted
            $recentBids = Bid::with(['vendor', 'tender'])
                ->latest('created_at')
                ->limit($limit)
                ->get()
                ->map(function ($bid) {
                    return [
                        'type' => 'bid_submitted',
                        'vendor' => $bid->vendor?->company_name,
                        'tender' => $bid->tender?->title,
                        'amount' => $bid->bid_amount,
                        'timestamp' => $bid->created_at,
                    ];
                });

            // Recent vendor approvals
            $recentVendors = Vendor::latest('approved_at')
                ->whereNotNull('approved_at')
                ->limit($limit)
                ->get()
                ->map(function ($vendor) {
                    return [
                        'type' => 'vendor_approved',
                        'company_name' => $vendor->company_name,
                        'timestamp' => $vendor->approved_at,
                    ];
                });

            // Recent tender results
            $recentResults = TenderResult::with(['tender', 'winner', 'selector'])
                ->latest('selected_at')
                ->limit($limit)
                ->get()
                ->map(function ($result) {
                    return [
                        'type' => 'tender_result',
                        'tender' => $result->tender?->title,
                        'winner' => $result->winner?->company_name,
                        'winning_bid' => $result->winning_bid,
                        'selected_by' => $result->selector?->name,
                        'timestamp' => $result->selected_at,
                    ];
                });

            $allActivities = collect()
                ->merge($recentTenders)
                ->merge($recentBids)
                ->merge($recentVendors)
                ->merge($recentResults)
                ->sortByDesc('timestamp')
                ->values()
                ->take($limit);

            return response()->json([
                'message' => 'Recent activities retrieved successfully',
                'data' => $allActivities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving recent activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get database connection and table information
     */
    public function getDatabaseInfo()
    {
        try {
            $connection = config('database.default');
            $dbName = config("database.connections.{$connection}.database");

            // Get table sizes if MySQL
            $tables = [];
            if ($connection === 'mysql') {
                $tables = DB::select(DB::raw(
                    "SELECT 
                        TABLE_NAME,
                        ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb
                    FROM information_schema.TABLES 
                    WHERE table_schema = '{$dbName}'
                    ORDER BY (data_length + index_length) DESC"
                ));
            }

            $dbInfo = [
                'connection' => $connection,
                'database' => $dbName,
                'host' => config("database.connections.{$connection}.host"),
                'port' => config("database.connections.{$connection}.port"),
                'tables' => $tables,
                'total_tables' => count($tables),
            ];

            return response()->json([
                'message' => 'Database information retrieved successfully',
                'data' => $dbInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving database information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tender participation statistics
     */
    public function getTenderParticipationStats()
    {
        try {
            $tendersWithParticipants = Tender::withCount('participants')
                ->with('timeline')
                ->get()
                ->map(function ($tender) {
                    return [
                        'id' => $tender->id,
                        'title' => $tender->title,
                        'status' => $tender->status,
                        'participants' => $tender->participants_count,
                        'registration_period' => $tender->timeline ? [
                            'start' => $tender->timeline->registration_start,
                            'end' => $tender->timeline->registration_end,
                        ] : null,
                    ];
                });

            $stats = [
                'total_tender_participants' => TenderParticipant::count(),
                'average_participants_per_tender' => round(
                    Tender::withCount('participants')->get()->avg('participants_count'),
                    2
                ),
                'tenders_with_participants' => $tendersWithParticipants,
            ];

            return response()->json([
                'message' => 'Tender participation statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving tender participation statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get health check status
     */
    public function getHealthStatus()
    {
        try {
            $health = [
                'database' => [
                    'status' => 'healthy',
                    'connection_test' => DB::connection()->getDatabaseName() ? true : false,
                ],
                'models' => [
                    'users' => User::count() >= 0,
                    'vendors' => Vendor::count() >= 0,
                    'tenders' => Tender::count() >= 0,
                    'bids' => Bid::count() >= 0,
                ],
                'timestamp' => now()->toIso8601String(),
            ];

            return response()->json([
                'message' => 'System health status retrieved successfully',
                'data' => $health
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving health status',
                'error' => $e->getMessage(),
                'status' => 'unhealthy'
            ], 500);
        }
    }
}
