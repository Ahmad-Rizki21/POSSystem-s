<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): Response
    {
        $user = auth()->user();

        // Get today's stats
        $today = Carbon::today();
        $stats = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalTransactions' => Transaction::whereDate('created_at', $today)->count(),
            'todayRevenue' => Transaction::whereDate('created_at', $today)->sum('total_price'),
        ];

        // Get recent transactions (admin only)
        $recentTransactions = [];
        if ($user->isAdmin()) {
            $recentTransactions = Transaction::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(callback: function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'user' => $transaction->user ? [
                            'name' => $transaction->user->name,
                        ] : null,
                        'total_price' => $transaction->total_price,
                        'created_at' => $transaction->created_at->toISOString(),
                    ];
                });
        }

        return Inertia::render('Dashboard', [
            'auth' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ],
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    /**
     * Get dashboard stats as API response.
     */
    public function stats(): JsonResponse
    {
        $today = Carbon::today();

        $stats = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalTransactions' => Transaction::whereDate('created_at', $today)->count(),
            'todayRevenue' => Transaction::whereDate('created_at', $today)->sum('total_price'),
        ];

        return response()->json($stats);
    }
}