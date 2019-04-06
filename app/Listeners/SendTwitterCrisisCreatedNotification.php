<?php

namespace App\Listeners;

use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use App\Crisis;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\TwitterController;
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
      
    }

    /**
     * Handle the event.
     *
     * @param  CrisisCreated  $event
     * @return void
     */
    public function handle(CrisisCreated $event)
    {
        $graph = new TwitterController();
        $crisis = $event->crisis;

        $message = "On  ".$crisis->date. " at ".$crisis->time . " there is a " . $crisis->crisis_type
         . " at " . $crisis->address. ". " . $crisis->description;
        
        $image = $crisis->image;

        $twit_id = $graph->tweet($message, $image);

        $updateCrisis = Crisis::whereId($crisis->id)->first();
        $updateCrisis->update(['twitter_post_id' => $twit_id]);
       
    }
    
  
    
}
