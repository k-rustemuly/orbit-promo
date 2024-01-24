<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;

class ProfileController extends BaseController
{
    public function profile()
    {
        return $this->success(new ProfileResource(auth()->user()));
    }
}
