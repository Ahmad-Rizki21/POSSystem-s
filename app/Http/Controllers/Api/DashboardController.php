<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $today = Carbon::today();

        $stats = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalTransactions' => Transaction::whereDate('created_at', $today)->count(),
            'todayRevenue' => Transaction::whereDate('created_at', $today)->sum('total_price') ?? 0,
        ];

        return response()->json($stats);
    }
}

