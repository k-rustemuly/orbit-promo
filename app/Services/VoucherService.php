<?php

namespace App\Services;

use App\Http\Resources\VouchersResource;
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

    public function all($search, ?User $user = null)
    {
        $voucher = Voucher::filter($search)
            ->with(['user', 'prize']);

        if($user) {
            $voucher->where('user_id', $user->id);
        }
        else{
            $voucher->whereNotNull('winned_date')->where('is_approved', true);
        }
        return VouchersResource::collection(
                $voucher
                    ->paginateFilter()
        )
        ->response()
        ->getData(true);
    }
}
