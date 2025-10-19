<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Storage;

class AuditTrailListener
{
    /**
     * Append a structured JSON line to storage logging the order placement.
     */
    public function handle(OrderPlaced $event): void
    {
        $data = [
            'event' => 'order_placed',
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'buyer_id' => $event->order->buyer_id,
            'total_amount' => $event->order->total_amount,
            'timestamp' => now()->toIso8601String(),
        ];

        $line = json_encode($data).PHP_EOL;
        Storage::makeDirectory('audit');
        Storage::append('audit/trail.log', $line);
    }
}
