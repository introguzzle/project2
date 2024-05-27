<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

abstract class QueueableListener implements ShouldQueue, Listener
{
    use InteractsWithQueue;
}
