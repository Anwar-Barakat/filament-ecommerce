<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number', 'customer_id', 'shipping_price', 'notes', 'total_price', 'status'
    ];


    // Relationships
    // An order belongs to a customer
    public function customer(): BelongsTo{
        return $this->belongsTo(Customer::class);
    }

    // An order has many order items
    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}
