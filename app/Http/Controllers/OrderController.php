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
        $order = $position->orders()->create($request->validated());

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
