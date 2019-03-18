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

        if($crisis->assistance_required == null){
            return;
        }
        
        $crisis = $event->crisis;
        $content = "There is currently a " . $crisis->crisis_type . " at " . $crisis->address. ". For more information, visit us at www.CrisisLookOut.com";
        
        $assistances = explode(",", $crisis->assistance_required);
    
        foreach($assistances as $assistance){
            $telephone_number = $assistance->telephone_number;
            $name = $assistance->name;
            $sms->sendSMS($assistance->telephone_number,  $content);
        }


    }
}
