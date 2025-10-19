<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationListener
{
    /**
     * Simulate email dispatch by logging the confirmation message.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;
        Log::info('Order confirmation queued', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);
    }
}
