<?php

namespace App\Observers;

use App\Models\Receipt;
use App\Models\ReceiptStatus;
use App\Models\User;
use App\Settings\GeneralSettings;

class ReceiptObserver
{
    public function created(Receipt $receipt)
    {
        if($receipt->receipt_status_id == ReceiptStatus::ACCEPTED) {
            $this->addLife($receipt->user);
        }
    }

    public function updated(Receipt $receipt)
    {
        $originalData = $receipt->getOriginal();
        if($originalData['receipt_status_id'] == ReceiptStatus::CHECKING && $receipt->receipt_status_id == ReceiptStatus::ACCEPTED) {
            $this->addLife($receipt->user);
        }
    }

    public function addLife(User $user)
    {
        $life = app(GeneralSettings::class)->receipt_life;
        $user->life+=$life;
        $user->save();
    }
}
