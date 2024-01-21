<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ReSendSms
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function __construct(public $user){}
}
