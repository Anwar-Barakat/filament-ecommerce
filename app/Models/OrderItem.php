<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price'
    ];

    // Relationships
    // An order item belongs to an order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
