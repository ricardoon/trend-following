<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;

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
        $asset = Asset::create($request->validated());

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
