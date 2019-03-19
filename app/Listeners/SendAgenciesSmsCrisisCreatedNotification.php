<?php

namespace App\Listeners;

use App\SMS;
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
        
        foreach($crisis->agency as $agency){
            $name = $agency->agency;
            $content = $name. ", There is currently a " . $crisis->crisis_type . " at " . $crisis->address . $crisis->postal_code;
            $telephone_number = $agency->hotline;
            $sms->sendSMS($telephone_number,  $content);
        }



    }
}
