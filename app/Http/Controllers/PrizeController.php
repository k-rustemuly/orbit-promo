<?php

namespace App\Http\Controllers;

use App\Http\Resources\InstantPrizesResource;
use App\Http\Resources\PrizesResource;
use App\Models\InstantPrize;
use App\Models\Prize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrizeController extends BaseController
{
    public function prizes()
    {
        return $this->success(PrizesResource::collection(Prize::all()));
    }

    public function instantPrizes(Request $request)
    {
        $instantPrize = InstantPrize::with('winner')
            ->filter(
                $request->all()
            )
            ->won();
        if(Auth::check()) {
            $instantPrize = $instantPrize->where('winner_id', auth()->id());
        }
        return $this->success(
            InstantPrizesResource::collection(
                $instantPrize
                    ->orderBy('winning_date', 'desc')
                    ->paginateFilter()
            )
            ->response()
            ->getData(true)
        );
    }

}
