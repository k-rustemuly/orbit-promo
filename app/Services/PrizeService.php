<?php

namespace App\Services;

use App\Models\Voucher;

class PrizeService
{
    public function draw($prize_id, $attemp = 1){
        $randomVoucher = Voucher::whereNull('winned_date')
            ->where('is_approved', false)
            ->where('prize_id', $prize_id)
            ->inRandomOrder()
            ->first();
        if(is_null($randomVoucher) ||
            Voucher::where('user_id', $randomVoucher->user_id)
                ->where('prize_id', $randomVoucher->prize_id)
                ->whereNotNull('winned_date')
                ->where('is_approved', true)
                ->exists()
        ) {
            if($attemp <=4) {
                return $this->draw($prize_id, $attemp++);
            } else {
                return false;
            }
        }
        $randomVoucher->winned_date = now();
        $randomVoucher->save();
        return true;
    }
}
