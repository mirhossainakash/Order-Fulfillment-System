<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Console\Commands;

use App\Jobs\GenerateInvoiceJob;
use App\Models\Order;
use Illuminate\Console\Command;

class InvoiceDailyCommand extends Command
{
    protected $signature = 'invoice:daily';

    protected $description = 'Generate invoices for paid but uninvoiced orders';

    public function handle(): int
    {
        $orders = Order::query()->where('status', 'paid')->where('invoiced', false)->get();
        foreach ($orders as $order) {
            GenerateInvoiceJob::dispatch($order->id);
            $this->info("Dispatched invoice job for order {$order->order_number}");
        }
        return self::SUCCESS;
    }
}
