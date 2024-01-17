<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Http\Requests\BalanceInstantPrizeStoreRequest;
use App\Http\Requests\ShowboxInstantPrizeStoreRequest;
use App\Models\InstantPrize;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class InstantPrizeController extends MoonShineController
{
    public function showboxStore(ShowboxInstantPrizeStoreRequest $request): Response
    {
        $data = $request->validated();
        for($i = 0; $i < $data['count']; $i++)
        {
            InstantPrize::create($data);
        }
        $this->toast(__('ui.messages.added'));
        return back();
    }

    public function balanceStore(BalanceInstantPrizeStoreRequest $request): Response
    {
        $data = $request->validated();
        $codes = array_unique(array_column($data['codes'], 'code'));
        $insert = collect($codes)->map(fn($code) =>
                [
                    'name_ru' => $data['name_ru'],
                    'name_kk' => $data['name_kk'],
                    'name_uz' => $data['name_uz'],
                    'code' => $code
                ]
        );
        InstantPrize::insert($insert->toArray());
        $this->toast(__('ui.messages.added_with_count', ['count' => $insert->count()]));
        return back();
    }


}
