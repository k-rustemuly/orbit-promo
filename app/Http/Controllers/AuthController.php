<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ReSendSmsRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function signUp(SignUpRequest $request, AuthService $service)
    {
        $request->ensureIsNotRateLimited();

        if($service->signUp($request->validated())) {
            RateLimiter::clear($request->throttleKey());
            return $this->success();
        }

        RateLimiter::hit($request->throttleKey());
        return $this->error(__('ui.messages.signUp_error'));
    }

    public function signIn(SignInRequest $request)
    {
        return $this->success([
            'token' => $request->authenticate()
        ]);
    }

    public function reSendSms(ReSendSmsRequest $request, AuthService $service)
    {
        $request->ensureIsNotRateLimited(1);

        if($service->reSendSms($request->validated()['phone_number'])) {
            RateLimiter::clear($request->throttleKey());
            return $this->success();
        }

        RateLimiter::hit($request->throttleKey());
        return $this->error(__('ui.messages.sms_limit'));
    }

    public function forgotPassword(ForgotPasswordRequest $request, AuthService $service)
    {
        $request->ensureIsNotRateLimited(1);
        $phone_number = $request->validated()['phone_number'];
        if($service->isVerifiedPhoneNumber($phone_number)) {
            if($email = $service->forgotPassword($phone_number)) {
                RateLimiter::clear($request->throttleKey());
                return $this->success(['email' => $email]);
            }
        }else {
            return $this->error(__('ui.messages.finish_registration'));
        }

        RateLimiter::hit($request->throttleKey());
        return $this->error(__('ui.messages.email_limit'));
    }
}
