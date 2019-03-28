<?php

namespace App\Listeners;

use App\Events\CrisisCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
//use Twitter;

require_once "vendor/autoload.php";
 
use Abraham\TwitterOAuth\TwitterOAuth;


define('CONSUMER_KEY', 'iXYAoYkQGLdo9JSgwlr64fLRM ');
define('CONSUMER_SECRET', 'lJWnl50ue4ui11qVhDLD3q5S3E5hpH3wMbePr4LCDkzUJALU02 ');
define('ACCESS_TOKEN', '1105731297719148544-aTlPLd5a4CzwChJaTx8XWwOGoue7lx (');
define('ACCESS_TOKEN_SECRET', 'mXrtEd5DHHkTr9u3EPDns8KNsK9AXeHW1eIrW8LhUJqw6 ');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
 

class SendTwitterCrisisCreatedNotification
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
    $status = 'This is a test tweet'; //text for your tweet.
	$post_tweets = $connection->post("statuses/update", ["status" => $status]);
		//$crisis = $event->crisis;
      //  $content = "There is currently a " . $crisis->crisis_type . " at " . $crisis->address. ". For more information, visit us at www.CrisisLookOut.com";
	//	Twitter::postTweet(array('status' => $content, 'format' => 'json'));
		//Twitter::postTweet(array('status' => 'Tweet sent using Laravel and the Twitter API!', 'format' => 'json'));
    }
}
