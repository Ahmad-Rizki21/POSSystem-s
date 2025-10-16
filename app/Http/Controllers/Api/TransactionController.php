<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Store a new transaction via API.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,qris,transfer,debit_card,credit_card',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total and validate stock
            $totalPrice = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}"
                    ], 400);
                }

                $totalPrice += $item['quantity'] * $item['price'];
            }

            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Transaction::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $totalPrice,
            ]);

            // Create transaction items and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully!',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'invoice_number' => $invoiceNumber,
                    'total_price' => $totalPrice,
                    'change_amount' => $request->paid_amount - $totalPrice
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction history.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['user', 'items.product'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->when($request->input('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->input('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Get specific transaction.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product.category']);

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Get today's transactions summary.
     */
    public function todaySummary()
    {
        $today = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        $transactions = Transaction::whereBetween('created_at', [$today, $endOfDay])->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_revenue' => $transactions->sum('total_price'),
            'total_paid' => $transactions->sum('paid_amount'),
            'total_change' => $transactions->sum('change_amount'),
            'payment_methods' => $transactions->groupBy('payment_method')->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('total_price')
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}
