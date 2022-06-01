<?php

namespace App\Http\Controllers;

use App\Http\Requests\HiloRequest;
use App\Http\Resources\HiloResource;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetHiloController extends Controller
{
    public function store(HiloRequest $request, Asset $assets)
    {
        $assets->hilo()->create($request);

        return new HiloResource($assets->hilo);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
