<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Lin\Binance\BinanceFuture;

class AssetController extends BaseController
{
    public function index()
    {
        $assets = Asset::all();

        return $this->sendResponse(
            AssetResource::collection($assets),
            'Assets retrieved successfully.'
        );
    }

    public function create()
    {
        //
    }

    public function store(AssetRequest $request)
    {
        $asset_validated = $request->validated();

        $binance = new BinanceFuture(config('binance.api_key'), config('binance.api_secret'));

        $exchange_info = $binance->trade()->getExchangeInfo();

        foreach ($exchange_info['symbols'] as $symbol) {
            if ($symbol['symbol'] == $asset_validated['code']) {
                $asset_validated['precision'] = $symbol['baseAssetPrecision'];
                break;
            }
        }

        $asset = Asset::create($asset_validated);

        return $this->sendResponse(
            new AssetResource($asset),
            'Asset created successfully.'
        );
    }

    public function bulkStore(AssetRequest $request)
    {
        $assets = Asset::insert($request->validated());

        return $this->sendResponse(
            $request->validated(),
            'Assets created successfully.'
        );
    }

    public function show(Asset $asset)
    {
        return $this->sendResponse(
            new AssetResource($asset),
            'Asset retrieved successfully.'
        );
    }

    public function edit($id)
    {
        //
    }

    public function update(AssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        return $this->sendResponse(
            new AssetResource($asset),
            'Asset updated successfully.'
        );
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return $this->sendResponse(
            new AssetResource($asset),
            'Asset deleted successfully.'
        );
    }
}
