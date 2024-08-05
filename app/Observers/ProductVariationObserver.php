<?php

namespace App\Observers;

use App\Models\ProductVariation;

class ProductVariationObserver
{
    public function creating(ProductVariation $productVariation): void
    {
        $productVariation->where('product_id', $productVariation->product_id)->max('order');
    }
}
