<?php

namespace App\Http\Controllers;

use App\Http\Resources\HiloResource;
use App\Models\Hilo;

class HiloController extends BaseController
{

    public function index()
    {
        $hilos = Hilo::all();

        return $this->sendResponse(
            HiloResource::collection($hilos),
            'Hilos retrieved successfully.'
        );
    }
}
