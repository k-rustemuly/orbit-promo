<?php

namespace App\Observers;

use App\Models\Voucher;

class VoucherObserver
{
    public function updating(Voucher $voucher)
    {
        if($voucher->isDirty('is_approved') && is_null($voucher->winned_date)) {
            return false;
        }
    }

    public function deleting(Voucher $voucher)
    {
        if( !is_null($voucher->winned_date)) {
            return false;
        }
    }
}
