<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 'user_id', 'table_id', 'customer_name', 'customer_phone',
        'total', 'tax', 'service_charge', 'grand_total', 'payment_amount', 'change_amount',
        'payment_method', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'tax' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'payment_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
        ];
    }

    public static function calculateTotals(array $cart, float $taxRate = 10): array
    {
        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $tax = $subtotal * ($taxRate / 100);
        $grandTotal = $subtotal + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'service_charge' => 0,
            'grand_total' => round($grandTotal, 2),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }



    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
