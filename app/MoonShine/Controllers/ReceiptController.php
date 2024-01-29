<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptStatus;
use MoonShine\Http\Controllers\MoonShineController;
use MoonShine\MoonShineRequest;
use Symfony\Component\HttpFoundation\Response;

final class ReceiptController extends MoonShineController
{
    public function approve(string $resourceUri, Receipt $receipt, MoonShineRequest $request): Response
    {
        if($receipt->receipt_status_id == ReceiptStatus::CHECKING) {
            $receipt->url = $request->get('url');
            $receipt->receipt_status_id = ReceiptStatus::ACCEPTED;
            $receipt->save();
            $this->toast(__('ui.messages.saved'));
        }else {
            $this->toast(__('ui.messages.error'), 'error');
        }
        return back();
    }

    public function reject(string $resourceUri, Receipt $receipt, MoonShineRequest $request): Response
    {
        if($receipt->receipt_status_id == ReceiptStatus::CHECKING) {
            $receipt->receipt_status_id = ReceiptStatus::NOT_FOUND;
            $receipt->save();
            $this->toast(__('ui.messages.saved'));
        }else {
            $this->toast(__('ui.messages.error'), 'error');
        }
        return back();
    }

}
