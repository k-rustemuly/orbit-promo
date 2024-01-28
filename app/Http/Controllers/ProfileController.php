<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailRequest;
use App\Http\Resources\InvitationsResource;
use App\Http\Resources\ProfileResource;
use App\Mail\FeadbackMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function mail(MailRequest $request)
    {
        $data = $request->validated();
        $workMail = config('settings.work_mail');
        Mail::to($workMail)->send(new FeadbackMail($data['email'], $data['text']));
        return $this->success();
    }
}
