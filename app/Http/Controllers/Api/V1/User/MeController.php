<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Resources\UserResource;

class MeController
{
    public function __invoke()
    {
        return UserResource::make(auth()->user());
    }

}
