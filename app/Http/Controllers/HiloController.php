<?php

namespace App\Http\Controllers;

use App\Http\Requests\HiloNotifyRequest;
use App\Http\Requests\HiloRequest;
use App\Http\Resources\HiloResource;
use App\Models\Asset;
use App\Models\Hilo;

class HiloController extends BaseController
{

    public function notify(HiloNotifyRequest $request, Asset $asset)
    {
        dd($asset->positions->where('granularity', $request->granularity));
        foreach ($asset->positions as $position) {
            $orders = $position->orders()->where('ended_at', null)->first();

            foreach ($orders as $order) {
                if ($order->side != $request->action) {
                    // change order side
                }
            }
        }

        return $this->sendResponse(null, 'Hilo notified successfully.');
    }

    public function index()
    {
        $hilos = Hilo::all();

        return $this->sendResponse(
            HiloResource::collection($hilos),
            'Hilos retrieved successfully.'
        );
    }

    public function show($asset_id, $granularity)
    {
        $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->first();

        if (!$hilo) {
            return $this->sendError('Hilo not found for this granularity.', 404);
        }

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo retrieved successfully.'
        );
    }

    public function store(HiloRequest $request, $asset_id)
    {
        if (Asset::find($asset_id) == null) {
            return $this->sendError('Asset not found. Please check the asset ID.');
        }

        $validated = $request->validated();
        $validated['asset_id'] = $asset_id;

        $hilo = Hilo::create($validated);

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo created successfully.'
        );
    }

    public function update(HiloRequest $request, $asset_id, $granularity)
    {
        $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->update($request->validated());

        if (!$hilo) {
            return $this->sendError('Hilo not found for this granularity.', 404);
        }

        $hilo = Hilo::where('asset_id', $asset_id)->where('granularity', $granularity)->first();

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo updated successfully.'
        );
    }
}
