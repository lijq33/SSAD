<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
   /**
     * @param $telephone the user's telephone number
     * @param $content the content to be sms
     * 
     * Send SMS to the Users 
     */
    public function sendSMS($telephone,  $content) {

        $basic  = new \Nexmo\Client\Credentials\Basic(env('NEXMO_API_KEY'), env('NEXMO_API_SECRET'));
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => '65'.$telephone,
            'from' => 'Crisis Alert',
            'text' => $content
        ]);
    }

}
