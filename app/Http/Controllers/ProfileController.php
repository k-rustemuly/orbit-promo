<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvitationsResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ReceiptsResource;
use App\Models\ReceiptStatus;

class ProfileController extends BaseController
{
    public function profile()
    {
        return $this->success(new ProfileResource(auth()->user()));
    }

    public function invitations()
    {
        /** @var \App\Model\User */
        $user = auth()->user();
        $invitations = $user->invitations()->with('friend')->get();
        return $this->success(InvitationsResource::collection($invitations));
    }

    public function receipts()
    {
        /** @var \App\Model\User */
        $user = auth()->user();
        $receipts = $user->receipts()->where('receipt_status_id', ReceiptStatus::ACCEPTED)->get();
        return $this->success(ReceiptsResource::collection($receipts));

    }
}
