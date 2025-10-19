<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $orders,
        protected ProductRepository $products,
    ) {}

    /**
     * Create an order from items: [[product_id, quantity]]
     * - Validates stock under row lock
     * - Creates order and items in a single transaction
     * - Dispatches OrderPlaced event after commit
     */
    public function placeOrder(User $buyer, array $items): Order
    {
        if (empty($items)) {
            throw new \InvalidArgumentException('Items array cannot be empty');
        }

        return DB::transaction(function () use ($buyer, $items) {
            $order = $this->orders->create([
                'order_number' => uniqid('ORD-'),
                'buyer_id' => $buyer->id,
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $total = 0;
            foreach ($items as $item) {
                $productId = (int)($item['product_id'] ?? 0);
                $qty = (int)($item['quantity'] ?? 0);
                if ($productId < 1 || $qty < 1) {
                    throw new \InvalidArgumentException('Each item must include product_id and quantity >= 1');
                }

                $product = $this->products->lockForUpdate($productId);
                $this->products->decrementStock($product, $qty);

                $price = (float)$product->price;
                $subtotal = $price * $qty;
                $total += $subtotal;

                $this->orders->addItem($order, [
                    'product_id' => $product->id,
                    'seller_id' => $product->user_id,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);

            // After commit, dispatch the event to avoid double processing on rollback
            DB::afterCommit(function () use ($order) {
                OrderPlaced::dispatch($order->fresh('items'));
            });

            return $order->fresh('items');
        });
    }

    /**
     * List orders for a user, scoped by type purchases|sales.
     */
    public function listOrdersFor(User $user, string $type)
    {
        $type = $type === 'sales' ? 'sales' : 'purchases';
        if ($type === 'sales') {
            return Order::query()
                ->whereHas('items', fn($q) => $q->where('seller_id', $user->id))
                ->with('items')
                ->latest()->paginate();
        }
        return Order::query()
            ->where('buyer_id', $user->id)
            ->with('items')
            ->latest()->paginate();
    }
}
