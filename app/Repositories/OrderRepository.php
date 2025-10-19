<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository
{
    public function create(array $attributes): Order
    {
        return Order::create($attributes);
    }

    public function addItem(Order $order, array $attributes): OrderItem
    {
        return $order->items()->create($attributes);
    }

    public function findOwnedByBuyerOrFail(int $id, int $buyerId): Order
    {
        return Order::query()->where('id', $id)->where('buyer_id', $buyerId)->firstOrFail();
    }

    public function findOrderWithAuthorizationScope(int $id, int $userId, string $role): Order
    {
        $query = Order::query()->with('items');
        if ($role === 'buyer') {
            $query->where('buyer_id', $userId);
        } else {
            $query->whereHas('items', function ($q) use ($userId) {
                $q->where('seller_id', $userId);
            });
        }
        return $query->findOrFail($id);
    }
}
