<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $transactions = StockTransaction::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Add image_url to each transaction's product
        $transactions->getCollection()->transform(function ($transaction) {
            if ($transaction->product) {
                $transaction->product->image_url = $transaction->product->image ? asset('storage/' . $transaction->product->image) : null;
            }
            return $transaction;
        });

        return Inertia::render('StockAdjustment/Index', [
            'transactions' => $transactions,
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    }

    public function create()
    {
        $products = Product::with('category')->get()->map(function ($product) {
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
            return $product;
        });

        return Inertia::render('StockAdjustment/Create', [
            'products' => $products,
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);

        // For 'out' transactions, check if enough stock is available
        if ($request->type === 'out' && $product->stock < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Insufficient stock. Available: ' . $product->stock
            ])->withInput();
        }

        // Record the stock transaction
        $product->recordStockTransaction(
            $request->type,
            $request->quantity,
            auth()->id(),
            $request->reference,
            $request->notes,
            $request->cost_price
        );

        return redirect()->route('stock-adjustment.index')
            ->with('success', 'Stock adjustment recorded successfully.');
    }

    public function show(StockTransaction $stockTransaction)
    {
        $stockTransaction->load(['product', 'user']);

        // Add image_url to the product if it exists
        if ($stockTransaction->product) {
            $stockTransaction->product->image_url = $stockTransaction->product->image ? asset('storage/' . $stockTransaction->product->image) : null;
        }

        return Inertia::render('StockAdjustment/Show', [
            'transaction' => $stockTransaction,
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    }

    public function history()
    {
        try {
            // Debug: Log access to this method
            \Log::info('StockAdjustmentController history method accessed');

            $products = Product::with('category')->get();
            \Log::info('Products retrieved: ' . $products->count());

            // Get recent stock transactions for display
            $recentTransactions = StockTransaction::with(['product', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Add image_url to each transaction's product
            $recentTransactions->transform(function ($transaction) {
                if ($transaction->product) {
                    $transaction->product->image_url = $transaction->product->image ? asset('storage/' . $transaction->product->image) : null;
                }
                return $transaction;
            });

            \Log::info('Recent transactions retrieved: ' . $recentTransactions->count());

            $user = auth()->user();
            if (!$user) {
                \Log::info('No authenticated user');
                abort(403, 'Unauthorized action.');
            }
            \Log::info('User authenticated: ' . $user->name);

            return Inertia::render('StockAdjustment/History', [
                'products' => $products,
                'recentTransactions' => $recentTransactions,
                'auth' => [
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in StockAdjustmentController history: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            abort(500, 'Internal server error');
        }
    }

    public function historyData(Request $request)
    {
        $query = StockTransaction::with(['product', 'user']);

        // Filter by product if specified
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by type if specified
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by date range if specified
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($transactions);
    }
}
