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

    public function show(Asset $asset)
    {
        $hilo = $asset->hilo;

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo retrieved successfully.'
        );
    }

    public function store(HiloRequest $request)
    {
        if (Asset::find($request->asset_id) == null) {
            return $this->sendError('Asset not found. Please check the asset_id.');
        }

        $hilo = Hilo::create($request->validated());

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo created successfully.'
        );
    }

    public function update(HiloRequest $request, Asset $asset)
    {
        Hilo::where('asset_id', $asset->id)->update($request->validated());
        $hilo = $asset->hilo;

        return $this->sendResponse(
            new HiloResource($hilo),
            'Hilo updated successfully.'
        );
    }
}
