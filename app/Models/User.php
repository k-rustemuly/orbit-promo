<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\VerifySms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'birthdate',
        'referral',
        'life',
        'level',
        'coin',
        'is_won_instant_prize',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number_verified_at' => 'datetime',
        'birthdate' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhoneNumber()
    {
        return ! is_null($this->phone_number_verified_at);
    }

    public function sendSms()
    {
        $this->notify(new VerifySms);
    }

    public function getCode()
    {
        return $this->remember_token;
    }

    public function routeNotificationForSms()
    {
        return $this->phone_number;
    }

    public function getHiddenEmailAttribute()
    {
        $email = $this->attributes['email'];
        [$username, $domain] = explode('@', $email);
        $hiddenUsername = Str::mask($username, '*', 3);
        return $hiddenUsername . '@' . $domain;
    }

    public function getHiddenPhoneNumberAttribute()
    {
        $phoneNumber = $this->attributes['phone_number'];
        if (substr($phoneNumber, 0, 1) === '7') {
            return '+7 ' . substr($phoneNumber, 1, 3) . ' XXX XX ' . substr($phoneNumber, -2);
        } elseif (substr($phoneNumber, 0, 1) === '9') {
            return '+998 XX ' . substr($phoneNumber, 2, 3) . ' XXX XX ' . substr($phoneNumber, -2);
        }
        return '';
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'owner_id', 'id');
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class, 'user_id', 'id');
    }
}
