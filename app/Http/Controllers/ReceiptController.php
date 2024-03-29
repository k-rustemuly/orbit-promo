<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRecognizeRequest;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReceiptsResource;
use App\Models\ReceiptStatus;
use Illuminate\Validation\ValidationException;

class ReceiptController extends BaseController
{
    public function recognize(ReceiptRecognizeRequest $request, ReceiptService $service)
    {
        $file = $request->file('file');
        if($request->get('is_manual')) {
            $service->file = $file;
            $service->url = '';
            $service->receipt_status_id = ReceiptStatus::CHECKING;
            $service->store(Auth::user());
            return $this->success();
        }
        else if(
            ($file && $result = $service->recognize($file) )
            || $result = $request->get('url')
        ) {
            if($service->isOfd($result)) {
                if($service->isUnique() && $service->isHavePosition($result, 'orbit')) {
                    $service->receipt_status_id = ReceiptStatus::ACCEPTED;
                    $service->store(Auth::user());
                    return $this->success();
                } else {
                    throw ValidationException::withMessages([
                        'file' => __('ui.messages.orbit_not_found'),
                    ]);
                }
            }
        }

        return $this->error(__('ui.messages.qr_not_found'));
    }

    public function receipts()
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $receipts = $user->receipts()->with('status')->get();
        return $this->success(ReceiptsResource::collection($receipts));

    }
}
