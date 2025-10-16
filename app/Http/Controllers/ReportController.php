<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Reports/Index');
    }

    /**
     * Display sales report.
     */
    public function salesReport(Request $request): Response
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay()->toDateString());
        $period = $request->input('period', 'monthly');

        // Sales Summary - Calculate profit based on selling price - purchase price
        $salesData = Transaction::join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(transactions.total_price) as total_sales'),
                DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as total_profit'),
                DB::raw('COUNT(DISTINCT transactions.id) as total_transactions')
            )
            ->first();

        $totalSales = $salesData->total_sales ?? 0;
        $totalTransactions = $salesData->total_transactions ?? 0;
        $totalProfit = $salesData->total_profit ?? 0;
        $averageTransactionValue = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Sales by Date/Period
        if ($period === 'daily') {
            $salesByPeriod = Transaction::select(
                    DB::raw('DATE(transactions.created_at) as period'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(transactions.total_price) as total_sales'),
                    DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as total_profit')
                )
                ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(transactions.created_at)'))
                ->orderBy('period')
                ->get();
        } elseif ($period === 'weekly') {
            $salesByPeriod = Transaction::select(
                    DB::raw('YEARWEEK(transactions.created_at) as period'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(transactions.total_price) as total_sales'),
                    DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as total_profit')
                )
                ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('YEARWEEK(transactions.created_at)'))
                ->orderBy('period')
                ->get();
        } else {
            $salesByPeriod = Transaction::select(
                    DB::raw('YEAR(transactions.created_at) as year'),
                    DB::raw('MONTH(transactions.created_at) as period'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(transactions.total_price) as total_sales'),
                    DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as total_profit')
                )
                ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('YEAR(transactions.created_at)'), DB::raw('MONTH(transactions.created_at)'))
                ->orderBy('year')
                ->orderBy('period')
                ->get();
        }

        // Top Cashiers
        $topCashiers = User::select('users.*',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_price) as total_sales'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)), 0) as total_profit')
            )
            ->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->leftJoin('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('users.id')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        // Recent Transactions
        $recentTransactions = Transaction::with(['user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return Inertia::render('Reports/Sales', [
            'totalSales' => (float) $totalSales,
            'totalTransactions' => $totalTransactions,
            'totalProfit' => (float) $totalProfit,
            'averageTransactionValue' => (float) $averageTransactionValue,
            'salesByPeriod' => $salesByPeriod,
            'topCashiers' => $topCashiers,
            'recentTransactions' => $recentTransactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period
        ]);
    }

    /**
     * Display product report.
     */
    public function productReport(Request $request): Response
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay()->toDateString());
        $categoryId = $request->input('category_id');

        // Product Performance Query
        $productPerformance = Product::select('products.*',
                'categories.name as category_name',
                DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * transaction_items.price), 0) as total_revenue'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)), 0) as total_profit'),
                DB::raw('COALESCE(COUNT(DISTINCT transaction_items.transaction_id), 0) as transaction_count')
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->leftJoin('transactions', function($join) use ($startDate, $endDate) {
                $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                     ->whereBetween('transactions.created_at', [$startDate, $endDate]);
            })
            ->when($categoryId, function($query, $categoryId) {
                return $query->where('products.category_id', $categoryId);
            })
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->get();

        // Category Performance
        $categoryPerformance = DB::table('categories')
            ->select('categories.*',
                DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * transaction_items.price), 0) as total_revenue'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)), 0) as total_profit'),
                DB::raw('COUNT(DISTINCT products.id) as product_count')
            )
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->leftJoin('transactions', function($join) use ($startDate, $endDate) {
                $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                     ->whereBetween('transactions.created_at', [$startDate, $endDate]);
            })
            ->groupBy('categories.id')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Out of Stock Products
        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Top Selling Products
        $topSellingProducts = $productPerformance->take(10);

        // Categories for filter
        $categories = \App\Models\Category::orderBy('name')->get();

        return Inertia::render('Reports/Products', [
            'productPerformance' => $productPerformance,
            'categoryPerformance' => $categoryPerformance,
            'topSellingProducts' => $topSellingProducts,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedCategory' => $categoryId
        ]);
    }

    /**
     * Display financial report.
     */
    public function financialReport(Request $request): Response
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay()->toDateString());
        $period = $request->input('period', 'monthly');

        // Debug: Log the dates being used
        \Log::info("Financial Report - Start Date: {$startDate}, End Date: {$endDate}, Period: {$period}");

        // Calculate profit based on selling price - purchase price
        $profitData = Transaction::join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as total_profit'),
                DB::raw('SUM(transaction_items.quantity * products.purchase_price) as total_cost'),
                DB::raw('SUM(transactions.total_price) as total_revenue')
            )
            ->first();

        $totalRevenue = $profitData->total_revenue ?? 0;
        $totalProfit = $profitData->total_profit ?? 0;
        $totalCost = $profitData->total_cost ?? 0;
        $totalTransactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Financial Performance by Period
        if ($period === 'daily') {
            $financialByPeriod = Transaction::select(
                    DB::raw('DATE(transactions.created_at) as period'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(transactions.total_price) as revenue'),
                    DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as profit'),
                    DB::raw('SUM(transaction_items.quantity * products.purchase_price) as cost')
                )
                ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(transactions.created_at)'))
                ->orderBy('period')
                ->get();
        } elseif ($period === 'weekly') {
            $financialByPeriod = Transaction::select(
                    DB::raw('YEARWEEK(transactions.created_at) as period'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(transactions.total_price) as revenue'),
                    DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as profit'),
                    DB::raw('SUM(transaction_items.quantity * products.purchase_price) as cost')
                )
                ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('YEARWEEK(transactions.created_at)'))
                ->orderBy('period')
                ->get();
        } else {
            $financialByPeriod = Transaction::select(
                    DB::raw('YEAR(transactions.created_at) as year'),
                    DB::raw('MONTH(transactions.created_at) as period'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('SUM(transactions.total_price) as revenue'),
                    DB::raw('SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)) as profit'),
                    DB::raw('SUM(transaction_items.quantity * products.purchase_price) as cost')
                )
                ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->join('products', 'transaction_items.product_id', '=', 'products.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('YEAR(transactions.created_at)'), DB::raw('MONTH(transactions.created_at)'))
                ->orderBy('year')
                ->orderBy('period')
                ->get();

            // Debug: Log the results
            \Log::info("Financial By Period Count: " . $financialByPeriod->count());
            \Log::info("Financial By Period Data: " . json_encode($financialByPeriod->toArray()));
        }

        // Payment Method Analysis
        $paymentMethods = Transaction::select(
                'payment_method',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('AVG(total_price) as average_transaction')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->orderBy('revenue', 'desc')
            ->get();

        // Cashier Performance
        $cashierPerformance = User::select('users.*',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_price) as revenue'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)), 0) as profit'),
                DB::raw('AVG(transactions.total_price) as average_transaction')
            )
            ->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
            ->leftJoin('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->leftJoin('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->groupBy('users.id')
            ->orderBy('revenue', 'desc')
            ->limit(10)
            ->get();

        // Top Profitable Products
        $topProfitableProducts = Product::select('products.*',
                'categories.name as category_name',
                DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as total_sold'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * transaction_items.price), 0) as revenue'),
                DB::raw('COALESCE(SUM(transaction_items.quantity * (transaction_items.price - products.purchase_price)), 0) as profit')
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->leftJoin('transactions', function($join) use ($startDate, $endDate) {
                $join->on('transaction_items.transaction_id', '=', 'transactions.id')
                     ->whereBetween('transactions.created_at', [$startDate, $endDate]);
            })
            ->groupBy('products.id')
            ->orderBy('profit', 'desc')
            ->limit(10)
            ->get();

        // Financial Metrics
        $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
        $averageTransactionValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        return Inertia::render('Reports/Financial', [
            'totalRevenue' => (float) $totalRevenue,
            'totalProfit' => (float) $totalProfit,
            'totalCost' => (float) $totalCost,
            'totalTransactions' => $totalTransactions,
            'profitMargin' => round($profitMargin, 2),
            'averageTransactionValue' => (float) $averageTransactionValue,
            'financialByPeriod' => $financialByPeriod,
            'paymentMethods' => $paymentMethods,
            'cashierPerformance' => $cashierPerformance,
            'topProfitableProducts' => $topProfitableProducts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period
        ]);
    }

    /**
     * API endpoint for report data
     */
    public function getData(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay()->toDateString());

        return response()->json([
            'totalSales' => Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_price'),
            'totalTransactions' => Transaction::whereBetween('created_at', [$startDate, $endDate])->count(),
            'topProducts' => Product::select('products.*', DB::raw('SUM(transaction_items.quantity) as total_sold'))
                ->leftJoin('transaction_items', 'products.id', '=', 'transaction_items.product_id')
                ->leftJoin('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->whereBetween('transactions.created_at', [$startDate, $endDate])
                ->groupBy('products.id')
                ->orderBy('total_sold', 'desc')
                ->limit(5)
                ->get()
        ]);
    }
}
