<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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

        // check if order for this asset already exists
        $order = $position->orders()->where('ended_at', null)->first();

        if ($order && $order->side == $order_validated['side']) {
            return $this->sendError(
                'Order already exists.',
                [
                    'order' => new OrderResource($order),
                ],
                422
            );
        }

        // $binance = new BinanceFuture(config('binance.api_key'), config('binance.api_secret'));
        $binance = new BinanceFuture(config('binance.test_api_key'), config('binance.test_api_secret'), 'https://testnet.binancefuture.com');

        // check if position already exists in binance
        try {
            $binance_position = $binance->trade()->getPosition([
                'symbol' => $position->asset->code,
            ]);
        } catch (\Exception $e) {
            return $this->sendError(
                'Error while checking position in Binance for the asset.',
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
            return $this->sendError('Position ' . $request->side . ' already exists in binance.', $binance_position[0], 422);
        }

        // get price from binance
        try {
            $asset = $binance->trade()->getMarkPrice([
                'symbol' => $position->asset->code,
            ]);
        } catch (\Exception $e) {
            return $this->sendError(
                'Error while getting price from Binance for the asset.',
                [
                    'asset' => $position->asset->code,
                ],
                422
            );
        }

        $quantity = round(($position->amount - 50) / $asset['markPrice'], $position->asset->precision, PHP_ROUND_HALF_DOWN);

        // check if we need to invert side
        if (
            ($binance_position[0]['positionAmt'] < 0 && $request->side == 'buy') ||
            ($binance_position[0]['positionAmt'] > 0 && $request->side == 'sell')
        ) {
            $quantity *= 2;
        }

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
                'side' => strtoupper($request->side),
                'type' => 'MARKET',
                'quantity' => $quantity,
            ]);
        } catch (\Exception $e) {
            return $this->sendError('Error creating Binance order.', $e->getMessage());
        }
        sleep(1);

        $order_validated['external_id'] = $result['orderId'];
        $order_validated['binance_client_order_id'] = $result['clientOrderId'];

        try {
            $binance_position = $binance->trade()->getPosition([
                'symbol' => $position->asset->code,
            ]);
        } catch (\Exception $e) {
            return $this->sendError('Error getting just created Binance order.', $e->getMessage());
        }

        // end last order if exists
        if ($order) {
            Order::where('id', $order->id)->update([
                'ended_at' => now(),
            ]);
        }

        $order_validated['started_at'] = now();
        $order_validated['entry_price'] = $binance_position[0]['entryPrice'];
        $order_validated['quantity'] = $binance_position[0]['positionAmt'];
        $order_validated['size'] = $binance_position[0]['isolatedMargin'];

        $new_order = $position->orders()->create($order_validated);

        return $this->sendResponse(
            new OrderResource($new_order),
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
