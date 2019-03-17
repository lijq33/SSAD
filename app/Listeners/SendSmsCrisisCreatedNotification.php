<?php

namespace App\Listeners;

use App\SMS;
use App\Subscriber;
use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;

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
      Log::info('=== TestEventListener  ========');
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
        var_dump("here");
        
        var_dump($event->crisis['crisis_type']. " was created.");

        Bugsnag::notifyException(new RuntimeException("Test error"));

        $sms = new SMS();

        if($subscribers->isEmpty()){
            return;
        }
        $crisis = $event->crisis;
        $content = "There is currently a " . $crisis->crisis_type . " at " . $crisis->address. ". For more information, visit us at www.CrisisLookOut.com";
     
        foreach($subscribers as $subscriber){
            $telephone_number = $subscriber->telephone_number;
            $name = $subscriber->name;
            $sms->sendSMS($subscriber->telephone_number,  $content);
        }



    }
}
