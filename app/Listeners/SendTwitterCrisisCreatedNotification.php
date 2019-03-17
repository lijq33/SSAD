<?php

namespace App\Listeners;

use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTwitterCrisisCreatedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CrisisCreated  $event
     * @return void
     */
    public function handle(CrisisCreated $event)
    {
        //Trigger twitter
    }
}
