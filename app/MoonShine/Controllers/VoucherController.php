<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\Voucher;
use App\Services\PrizeService;
use MoonShine\MoonShineRequest;
use MoonShine\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class VoucherController extends MoonShineController
{
    public function approve(string $resourceUri, Voucher $voucher): Response
    {
        if(! is_null($voucher->winned_date) && !$voucher->is_approved)
        {
            $voucher->is_approved = true;
            $voucher->save();
        }
        $this->toast(__('ui.messages.saved'));
        return back();
    }

    public function reject(string $resourceUri, Voucher $voucher, PrizeService $service): Response
    {
        if(! is_null($voucher->winned_date) && !$voucher->is_approved)
        {
            $voucher->winned_date = null;
            $voucher->save();
            $service->draw($voucher->prize_id);
        }
        $this->toast(__('ui.messages.saved'));
        return back();
    }
}
