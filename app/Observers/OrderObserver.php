<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function creating(Order $order): void
    {
        if (empty($order->order_number)) {
            $order->order_number = uniqid('ORD-');
        }
    }
}
