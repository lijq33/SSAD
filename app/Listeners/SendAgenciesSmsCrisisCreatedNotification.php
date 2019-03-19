<?php

namespace App\Listeners;

use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAgenciesSmsCrisisCreatedNotification
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
        $sms = new SMS();

        $crisis = $event->crisis;

        if($crisis->agency == null){
            return;
        }
        
        $content = "There is currently a " . $crisis->crisis_type . " at " . $crisis->address . $crisis->postal_code ;

        foreach($crisis->agency as $agency){
            $telephone_number = $assistance->telephone_number;
            $name = $assistance->name;
            $sms->sendSMS($assistance->telephone_number,  $content);
        }



    }
}
