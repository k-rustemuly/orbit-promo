<?php

namespace App\Services;

use App\Events\ReSendSms;
use App\Helpers\Generate;
use App\Mail\ForgotPasswordMail;
use App\Models\MailSended;
use App\Models\Sms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    /**
     * @param array $data
     *
     * @return bool
     */
    public function signUp(array $data): bool
    {
        $code = Generate::code();
        $data['password'] = $code;
        $data['remember_token'] = $code;
        if($user = User::where('phone_number', $data['phone_number'])->first()) {
            if(
                $user->hasVerifiedPhoneNumber()
                || ($user->email != $data['email']
                        && User::where('email', $data['email'])->exists()
                    )
            ) {
                return false;
            }
            $user->remember_token = $code;
            $user->password = $code;
            $user->save();
            event(new ReSendSms($user));
            return true;
        }else if($user = User::where('email', $data['email'])->first()) {
            return false;
        } else if($user = User::create($data)) {
            event(new Registered($user));
            return true;
        }
        return false;
    }

    /**
     *
     * @param string phone_number
     *
     * @return bool
     */
    public function reSendSms(string $phone_number): bool
    {
        $user = User::where('phone_number', $phone_number)->first();
        if($user && ! $user->hasVerifiedPhoneNumber()) {
            $sms = Sms::where('phone_number', $phone_number)
                ->orderBy('id', 'desc')
                ->first();

            if ($sms && Carbon::now()->diffInSeconds($sms->created_at) < 60) {
                return false;
            }
            $code = Generate::code();
            $user->remember_token = $code;
            $user->password = $code;
            $user->save();
            event(new ReSendSms($user));

            return true;

        }
        return false;
    }

    /**
     *
     * @param string phone_number
     *
     * @return string|null
     */
    public function forgotPassword(string $phone_number): ?string
    {
        $user = User::where('phone_number', $phone_number)->first();
        if($user) {
            $lastMail = MailSended::where('email', $user->email)
                    ->orderBy('id', 'desc')
                    ->first();
            if ($lastMail && Carbon::now()->diffInSeconds($lastMail->created_at) < 60) {
                return null;
            }

            $code = Generate::code();
            $user->password = $code;
            $user->save();
            $response = Mail::to($user->email)->send(new ForgotPasswordMail($user->name, $code));
            if($response) {
                MailSended::create([
                    'email' => $user->email,
                    'msg' => $code
                ]);
                return $user->hiddenEmail;
            }
        }
        return null;
    }

    public function isVerifiedPhoneNumber(string $phone_number): bool
    {
        $user = User::where('phone_number', $phone_number)->first();
        return (bool) $user && $user->hasVerifiedPhoneNumber();
    }

}
