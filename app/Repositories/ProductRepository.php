<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository
{
    public function findOrFail(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    public function lockForUpdate(int $id): Product
    {
        $product = Product::query()->whereKey($id)->lockForUpdate()->first();
        if (!$product) {
            throw (new ModelNotFoundException())->setModel(Product::class, [$id]);
        }
        return $product;
    }

    public function decrementStock(Product $product, int $quantity): void
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1');
        }
        if ($product->stock_quantity < $quantity) {
            throw new \RuntimeException("Insufficient stock for product {$product->id}");
        }
        $product->decrement('stock_quantity', $quantity);
        $product->refresh();
    }
}
