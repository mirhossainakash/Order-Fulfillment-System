<?php

// Developer: Md. Mir Hossain | Reviewed: 2025-10-19

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    /**
     * GET /api/orders?type=purchases|sales
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        $user = $request->user();
        $type = $request->query('type', $user->role === 'seller' ? 'sales' : 'purchases');
        return response()->json($this->service->listOrdersFor($user, $type));
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);
        return response()->json($order->load('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => ['required','array','min:1'],
            'items.*.product_id' => ['required','integer','min:1'],
            'items.*.quantity' => ['required','integer','min:1'],
        ]);

        $this->authorize('create', Order::class);
        $order = $this->service->placeOrder($request->user(), $data['items']);
        return response()->json($order->load('items'), 201);
    }
}
