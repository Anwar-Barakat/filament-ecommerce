<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductVariation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'parent_id',
        'product_id',
        'title',
        'slug',
        'type',
        'sku',
        'order',
        'price',
        'parent_id'
    ];

    public static function boot(){
        parent::boot();
    }

    public function parent() {
        return $this->belongsTo(ProductVariation::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
