<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRecognizeRequest;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\Auth;

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
}
