<?php

namespace App\Listeners;

use App\SMS;
use App\Subscriber;
use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsCrisisCreatedNotification
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
        //retrieve all subscribers
        $subscribers = Subscriber::all();

        $sms = new SMS();

        if($subscribers->isEmpty()){
            return;
        }
        
        $crisis = $event->crisis;
        $content = "There is currently a " . $crisis->crisis_type . " at " . $crisis->address. ". For more information, visit us at https://teamten.appspot.com ";
    
        foreach($subscribers as $subscriber){
            $telephone_number = $subscriber->telephone_number;
            $name = $subscriber->name;
            $sms->sendSMS($subscriber->telephone_number,  $content);
        }
    }
}
