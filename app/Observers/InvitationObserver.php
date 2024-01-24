<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Models\User;
use App\Settings\GeneralSettings;

class InvitationObserver
{
    public function __construct(public GeneralSettings $settings){}

    public function creating(Invitation $invitation)
    {
        $invitation->life = $this->settings->referal_life;
    }

    public function created(Invitation $invitation)
    {
        $owner = User::find($invitation->owner_id);
        $owner->life += $invitation->life;
        $owner->save();
    }
}
