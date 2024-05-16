<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public static function getSKU()
    {
        $product = Product::query()->orderByDesc('sku')->first();

        $urutan = $product ? (int) substr($product->sku, 3, 3) : 0;

        $urutan++;

        $huruf = "BRG";
        $sku   = $huruf . sprintf("%03s", $urutan);

        return $sku;
    }
}
