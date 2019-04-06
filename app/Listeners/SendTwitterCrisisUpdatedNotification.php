<?php

namespace App\Listeners;

use App\Events\CrisisUpdated;
use App\Crisis;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\TwitterController;
use Twitter;

class SendTwitterCrisisUpdatedNotification
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
     * @param  CrisisUpdated  $event
     * @return void
     */
    public function handle(CrisisUpdated $event)
    {
        $graph = new TwitterController();
        $crisis = $event->crisis;


        $message = "Update for the ".$crisis->crisis_type. " on ".$crisis->date 
        . " at " . $crisis->address. ", " . $crisis->description;
        
        $image = $crisis->image;

        $twit_id = $graph->postRt($crisis->twitter_post_id,$message,$image);

        $updateCrisis = Crisis::whereId($crisis->id)->first();
        $updateCrisis->update(['twitter_post_id' => $twit_id]);
    }
}
