<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRecognizeRequest;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReceiptsResource;
use App\Models\ReceiptStatus;

class ReceiptController extends BaseController
{
    public function recognize(ReceiptRecognizeRequest $request, ReceiptService $service)
    {
        if($service->isAccesed($request->file('file'))) {
            $service->store(Auth::user());
            return $this->success();
        }
        return $this->error();
    }

    public function receipts()
    {
        /** @var \App\Model\User */
        $user = auth()->user();
        $receipts = $user->receipts()->where('receipt_status_id', ReceiptStatus::ACCEPTED)->get();
        return $this->success(ReceiptsResource::collection($receipts));

    }
}
