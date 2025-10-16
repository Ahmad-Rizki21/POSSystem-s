<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'purchase_price',
        'selling_price',
        'stock',
        'unit',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    // Method to record stock transaction
    public function recordStockTransaction($type, $quantity, $userId, $reference = null, $notes = null, $costPrice = null)
    {
        $stockBefore = $this->stock;

        if ($type === 'in') {
            $stockAfter = $stockBefore + $quantity;
        } elseif ($type === 'out') {
            $stockAfter = $stockBefore - $quantity;
        } else { // adjustment
            $stockAfter = $quantity;
        }

        $transaction = $this->stockTransactions()->create([
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference' => $reference,
            'notes' => $notes,
            'cost_price' => $costPrice,
        ]);

        // Update product stock
        $this->update(['stock' => $stockAfter]);

        return $transaction;
    }
}