<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Resources\AssetResource;
use App\Libraries\Binance;
use App\Models\Asset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $binance = new Binance(config('binance.api_key'), config('binance.api_secret'));

        $exchange_info = $binance->trade()->getExchangeInfo();

        $asset_code = '';
        foreach ($exchange_info['symbols'] as $symbol) {
            if ($symbol['symbol'] == $asset_validated['code']) {
                $asset_code = $symbol['baseAsset'];
                $asset_validated['price_precision'] = $symbol['pricePrecision'];
                $asset_validated['quantity_precision'] = $symbol['quantityPrecision'];
                $asset_validated['quote_precision'] = $symbol['quotePrecision'];
                break;
            }
        }

        if (!isset($asset_validated['price_precision'])) {
            return $this->sendError(
                'Asset ' . $asset_validated['code'] . ' not found in Binance.',
                [
                    'binance_symbols' => $exchange_info['symbols'],
                ],
                422
            );
        }

        // download crypto asset image
        $filename = $asset_validated['category'] . '/';
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('get', 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/info?symbol=' . $asset_code, [
                'headers' => [
                    'X-CMC_PRO_API_KEY' => config('coinmarketcap.api_key'),
                ],
            ]);
            $response_body = json_decode($response->getBody()->getContents(), true);
            foreach ($response_body['data'] as $data) {
                $logo_url = $data[0]['logo'];
                break;
            }
            $filename .= $asset_code . '.png';
            $contents = file_get_contents($logo_url);
            Storage::disk('public')->put($filename, $contents);
            $asset_validated['image'] = $filename;
        } catch (\Exception $e) {
            $asset_validated['image'] = $filename . 'default.png';
            Log::channel('slack')->alert("Can't download logo for asset " . $asset_code, [
                'error' => $e->getMessage(),
            ]);
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
