<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyVoucherRequest;
use App\Services\VoucherService;

class VoucherController extends BaseController
{
    public function buy(BuyVoucherRequest $request, VoucherService $service)
    {
        $prizeId = $request->validated()['id'];
        if($service->buy($prizeId, auth()->user())) {
            return $this->success();
        }
        return $this->error(__('ui.messages.not_enough_coins'));
    }
}
