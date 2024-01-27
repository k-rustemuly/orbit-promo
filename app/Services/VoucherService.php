<?php

namespace App\Services;

use App\Models\User;
use App\Models\Prize;
use App\Models\Voucher;

class VoucherService
{
    public function buy($prizeId, User $user): bool
    {
        $prize = Prize::find($prizeId);
        $bal = $prize->bal;
        if($user->coin >= $bal) {
            $voucher = Voucher::create([
                'user_id' => $user->id,
                'prize_id' => $prizeId,
                'spent_balls' => $bal,
            ]);
            if($voucher) {
                $user->coin-=$bal;
                $user->save();
                return true;
            }
        }
        return false;
    }
}
