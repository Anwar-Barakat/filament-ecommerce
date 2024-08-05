<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'brand_id', 'title', 'slug', 'sku', 'description', 'image', 'quantity', 'price', 'is_visible',
        'is_featured', 'type', 'published_at', 'meta_description', 'variants',
        'user_id'
    ];

    protected $casts = [
        'published_at' => 'date',
        'variants' => 'array',
        'content' => 'array',
    ];

    // Relationships
    // A product belongs to a brand
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // A product belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // A product belongs to many categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    // A product has many product variations
    public function productVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

}
