<?php

namespace App\Events;

use App\Models\User\Identity;

class RegisteredEvent extends Event
{

    public readonly Identity $identity;

    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }
}
