<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BaseFormRequest extends FormRequest
{

    public function attributes()
    {
        $keys = array_keys($this->rules());
        return array_combine($keys, array_map(
            fn($key) => __('attributes.'.$key),
            $keys
            )
        );
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            str($this->input('phone_number') . '|' . $this->ip())
                ->lower()
        );
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param int $maxAttempts
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(int $maxAttempts = 5): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone_number' => trans('ui.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     *
     * @throws ValidationException
     */
    public function authenticate(): ?string
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            'phone_number' => request('phone_number'),
            'password' => request('password'),
        ];

        if (! Auth::attempt($credentials, true)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone_number' => __('ui.messages.auth_failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        /** @var \App\Models\User */
        $user = Auth::user();

        if(! $user->hasVerifiedPhoneNumber()) {
            $user->phone_number_verified_at = now();
            $user->remember_token = null;
            $user->save();
        }

        return $user->createToken('api-token')->plainTextToken;
    }
}
