<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvitationsResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success();
    }
}
