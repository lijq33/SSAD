<?php

namespace App\Listeners;

use App\Events\CrisisUpdated;
use App\Crisis;
use App\Http\Controllers\GraphController;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFacebookCrisisUpdatedNotification
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
        //
        $graph = new GraphController();
        $crisis = $event->crisis;

        $message = "Update for the ".$crisis->crisis_type. " on ".$crisis->date 
         . " at " . $crisis->address. ", " . $crisis->description;
        
        $imageName = $crisis->image;
        
        $graph->updatePost($message, $crisis->facebook_post_id, $imageName);
    }
}
