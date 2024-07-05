<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'name', 'slug', 'sku', 'description', 'image', 'quantity', 'price', 'is_visible', 'is_featured', 'type', 'published_at'
    ];

    // Relationships
    // A product belongs to a brand
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // A product belongs to many categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
