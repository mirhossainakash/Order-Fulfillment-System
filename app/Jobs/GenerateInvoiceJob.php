<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $orderId) {}

    public function handle(): void
    {
        $order = Order::with('items')->find($this->orderId);
        if (!$order || $order->invoiced || $order->status !== 'paid') {
            return; // nothing to do
        }

        $payload = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'buyer_id' => $order->buyer_id,
            'total_amount' => $order->total_amount,
            'items' => $order->items->map(fn($i) => [
                'product_id' => $i->product_id,
                'seller_id' => $i->seller_id,
                'price' => $i->price,
                'quantity' => $i->quantity,
                'subtotal' => $i->subtotal,
            ])->all(),
            'generated_at' => now()->toIso8601String(),
        ];

        $dir = 'invoices';
        Storage::makeDirectory($dir);
        Storage::put($dir.'/invoice-'.$order->order_number.'.json', json_encode($payload, JSON_PRETTY_PRINT));

        $order->forceFill(['invoiced' => true])->save();
    }
}
