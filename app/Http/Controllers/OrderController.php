<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Position;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public function index(Position $position)
    {
        $orders = $position->orders;

        return $this->sendResponse(
            OrderResource::collection($orders),
            'Orders retrieved successfully.'
        );
    }

    public function create()
    {
        //
    }

    public function store(OrderRequest $request, Position $position)
    {
        $order_validated = $request->validated();

        // check if order already exists
        $order = $position->orders()->where([
            'external_id' => $order_validated['external_id'],
            'binance_client_order_id' => $order_validated['binance_client_order_id'],
            'side' => $order_validated['side']
        ])->first();

        if ($order) {
            return $this->sendError(
                'Order already exists.',
                [
                    'order' => new OrderResource($order->first()),
                ],
                422
            );
        }

        $order = $position->orders()->create($order_validated);

        return $this->sendResponse(
            new OrderResource($order),
            'Order created successfully.'
        );
    }

    public function show(Position $position, $external_id)
    {
        $order = $position->orders()->where('external_id', $external_id)->first();

        return $this->sendResponse(
            new OrderResource($order),
            'Order retrieved successfully.'
        );
    }

    public function edit($id)
    {
        //
    }

    public function update(OrderRequest $request, Position $position, $external_id)
    {
        $order = $position->orders()->where('external_id', $external_id)->first();

        if (!$order) {
            return $this->sendError('Order not found.');
        }

        $order->update($request->validated());

        return $this->sendResponse(
            new OrderResource($order),
            'Order updated successfully.'
        );
    }

    public function destroy(Position $position, $external_id)
    {
        $order = $position->orders()->where('external_id', $external_id)->first();

        $order->delete();

        return $this->sendResponse(
            new OrderResource($order),
            'Order deleted successfully.'
        );
    }
}
