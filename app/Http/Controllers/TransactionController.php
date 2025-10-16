<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function create(): Response
    {
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return Inertia::render('POS/Create', [
            'products' => $products
        ]);
    }

    /**
     * Store a new transaction.
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
                    return back()->withErrors([
                        'message' => "Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}"
                    ]);
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

            return redirect()->route('pos.create')
                ->with('success', "Transaction completed successfully! Invoice: {$invoiceNumber}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'message' => 'Transaction failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display transaction history.
     */
    public function index(Request $request): Response
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

        return Inertia::render('POS/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'date_from', 'date_to'])
        ]);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction): Response
    {
        $transaction->load(['user', 'items.product.category']);

        return Inertia::render('POS/Show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Generate invoice PDF (placeholder for now)
     */
    public function invoice(Transaction $transaction)
    {
        // TODO: Implement PDF generation
        return back()->with('info', 'Invoice download will be available soon.');
    }
}
