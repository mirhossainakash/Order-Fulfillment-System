<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Listeners\AuditTrailListener;
use App\Listeners\SendOrderConfirmationListener;
use App\Listeners\UpdateSellerBalanceListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [
            UpdateSellerBalanceListener::class,
            SendOrderConfirmationListener::class,
            AuditTrailListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
