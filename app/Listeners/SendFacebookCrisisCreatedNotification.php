<?php

namespace App\Listeners;

use App\Events\CrisisCreated;
use App\Crisis;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\GraphController;

class SendFacebookCrisisCreatedNotification
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
        $graph = new GraphController();
        $crisis = $event->crisis;


        $message = "On  ".$crisis->date. " at ".$crisis->time . " there is a " . $crisis->crisis_type
         . " at " . $crisis->address. ". " . $crisis->description;

        $image = $crisis->image;
        $postid = $graph->publishToPage($message,  $image);
        $updateCrisis = Crisis::whereId($crisis->id)->first();
        $updateCrisis->update(['facebook_post_id' => $postid]);
    }
}
