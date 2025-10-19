<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
    }
}
