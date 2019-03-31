<?php

use Illuminate\Http\Request;
namespace App\Http\Controllers;
use File;
use Twitter;

class TwitterController extends Controller
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function tweet($msg,$img)
    {
    	
    $uploaded_media = Twitter::uploadMedia(['media' => File::get(public_path().'\crisis\\'.$img)]);   
    $newTwitte = ['status' => $msg , 'media_ids' => $uploaded_media->media_id_string];

    $twitter = Twitter::postTweet($newTwitte);
    

    
    return back();
    }
	
    	
}
