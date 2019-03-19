<?php

namespace App\Listeners;

use App\SMS;
use App\Subscriber;
use App\Events\CrisisUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsCrisisUpdatedNotification
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
        //retrieve all subscribers
        $subscribers = Subscriber::all();

        $sms = new SMS();

        if($subscribers->isEmpty()){
            return;
        }
        
        //Crisis
        $crisis = $event->crisis;

        $content = "Updates. The crisis" .$crisis->status. ". For more information, visit us at www.CrisisLookOut.com";

        foreach($subscribers as $subscriber){
            $telephone_number = $subscriber->telephone_number;
            $name = $subscriber->name;
            $sms->sendSMS($subscriber->telephone_number,  $content);
        }
    }
}
