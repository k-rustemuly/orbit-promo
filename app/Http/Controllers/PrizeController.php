<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrizesResource;
use App\Models\Prize;

class PrizeController extends BaseController
{
    public function prizes()
    {
        return $this->success(PrizesResource::collection(Prize::all()));
    }
}
