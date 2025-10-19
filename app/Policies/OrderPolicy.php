<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Allow listing orders for both roles; controller will scope results.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['buyer','seller'], true);
    }

    /**
     * Buyers can view their purchases; sellers can view orders where they sold an item.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->role === 'buyer') {
            return $order->buyer_id === $user->id;
        }

        // seller
        return $order->items()->where('seller_id', $user->id)->exists();
    }

    /**
     * Index scoping is handled at query level; store allowed for buyers only.
     */
    public function create(User $user): bool
    {
        return $user->role === 'buyer';
    }
}
