<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Position;
use Lin\Binance\Binance;
use Lin\Binance\BinanceFuture;

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

        dd($position);

        // check if order for this asset already exists
        $order = $position->orders()->where([
            'side' => $order_validated['side'],
            'ended_at' => null,
        ])->first();

        if ($order) {
            return $this->sendError(
                'Order already exists.',
                [
                    'order' => new OrderResource($order),
                ],
                422
            );
        }

        $binance = new BinanceFuture(config('binance.api_key'), config('binance.api_secret'));

        // check if position exists in binance
        try {
            $binance_position = $binance->trade()->getPosition([
                'symbol' => $position->asset->code,
            ]);
        } catch (\Exception $e) {
            return $this->sendError(
                'Error while checking position in binance for the asset.',
                [
                    'asset' => $position->asset->code,
                ],
                422
            );
        }

        if (
            ($binance_position[0]['positionAmt'] > 0 && $request->side == 'buy') ||
            ($binance_position[0]['positionAmt'] < 0 && $request->side == 'sell')
        ) {
            return $this->sendError('Position already exists in binance.', $binance_position[0], 422);
        }
        die;

        // try to create order on Binance
        try {
            // Make sure margin type is ISOLATED
            $binance->trade()->postMarginType([
                'symbol' => $position->asset->code,
                'marginType' => 'ISOLATED',
            ]);
            // Make sure leverage is set to 2x
            $binance->trade()->postLeverage([
                'symbol' => $position->asset->code,
                'leverage' => 2,
            ]);
            $result = $binance->trade()->postOrder([
                'symbol' => $position->asset->code,
                'side' => 'SELL',
                'type' => 'MARKET',
                'quantity' => '0.001',
            ]);
            print_r($result);
        } catch (\Exception $e) {
            return $this->sendError('Error creating order.', $e->getMessage());
        }
        sleep(1);

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
