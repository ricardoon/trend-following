<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;

class PositionController extends BaseController
{
    public function index()
    {
        $positions = Auth::user()->positions;

        return $this->sendResponse(
            PositionResource::collection($positions),
            'Positions retrieved successfully.'
        );
    }

    public function create()
    {
        //
    }

    public function store(PositionRequest $request)
    {
        $position = Auth::user()->positions()->where([
            'asset_id' => $request->asset_id,
            'ended_at' => null,
        ]);

        if ($position->exists()) {
            return $this->sendError(
                'Position already exists.',
                [
                    'position' => new PositionResource($position->first()),
                ],
                422
            );
        }

        $position = Auth::user()->positions()->create($request->validated());

        return $this->sendResponse(
            new PositionResource($position),
            'Position created successfully.'
        );
    }

    public function show(Position $position)
    {
        return $this->sendResponse(
            new PositionResource($position),
            'Position retrieved successfully.'
        );
    }

    public function edit($id)
    {
        //
    }

    public function update(PositionRequest $request, Position $position)
    {
        $position->update($request->validated());

        return $this->sendResponse(
            new PositionResource($position),
            'Position updated successfully.'
        );
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return $this->sendResponse(
            new PositionResource($position),
            'Position deleted successfully.'
        );
    }
}
