<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyVoucherRequest;
use App\Services\VoucherService;
use Illuminate\Http\Request;

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

    public function vouchers(Request $request, VoucherService $service)
    {
        return $this->success($service->all($request->all()));
    }

    public function myVouchers(Request $request, VoucherService $service)
    {
        return $this->success($service->all($request->all(), auth()->user()));

    }
}
