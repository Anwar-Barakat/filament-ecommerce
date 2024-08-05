<?php

namespace App\Providers;

use App\Models\ProductVariation;
use App\Observers\ProductVariationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $observers = [
        ProductVariation::class => ProductVariationObserver::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        foreach ($this->observers as $model => $observer) {
            $model::observe($observer);
        }
    }
}
