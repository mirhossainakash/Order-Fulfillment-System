<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Listeners;

use App\Events\OrderPlaced;

class UpdateSellerBalanceListener
{
    /**
     * Update each seller's balance based on their sold items in the order.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load('items');
        $sums = [];
        foreach ($order->items as $item) {
            $sums[$item->seller_id] = ($sums[$item->seller_id] ?? 0) + (float)$item->subtotal;
        }
        foreach ($sums as $sellerId => $amount) {
            \App\Models\User::query()->whereKey($sellerId)->increment('balance', $amount);
        }
    }
}
