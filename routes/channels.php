<?php

use App\Models\User\Identity;
use App\Models\User\Profile;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('orders', static function (Identity $identity, int $id) {
    return $identity->profile?->isAdmin() ?? false;
});
