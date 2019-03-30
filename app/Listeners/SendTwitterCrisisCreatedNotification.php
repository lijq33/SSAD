<?php

namespace App\Listeners;

use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twitter;

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
		$crisis = $event->crisis;
        $content = "There is currently a " . $crisis->crisis_type . " at " . $crisis->address;
		Twitter::postTweet(array('status' => $content, 'format' => 'json'));
    }
}
