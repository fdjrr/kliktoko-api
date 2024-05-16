<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function creating(Product $product)
    {
        $product->sku     = ProductService::getSKU();
        $product->user_id = Auth::user()->id;
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // ...
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // ...
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // ...
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        // ...
    }

    /**
     * Handle the Product "forceDeleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        // ...
    }
}
