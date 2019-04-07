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
        
        if($img != NULL){
            $uploaded_media = Twitter::uploadMedia(['media' => File::get(public_path().'/'.'crisis/'.$img)]);   
            $newTwitte = ['status' => $msg , 'media_ids' => $uploaded_media->media_id_string];
        }
        else{
            $newTwitte = ['status' => $msg];
        }

        $twitter = Twitter::postTweet($newTwitte);

        return $twitter->id_str;
    }

    public function postRt($id, $msg, $img)
    {

        if($img != NULL){
            $uploaded_media = Twitter::uploadMedia(['media' => File::get(public_path().'/'.'crisis/'.$img)]);   
            $newTwitte = ['status' => $msg, 'in_reply_to_status_id' => $id, 'media_ids' => $uploaded_media->media_id_string];
        }
        else{
            $newTwitte = ['status' => $msg, 'in_reply_to_status_id' => $id, ];    
        }

        $twitter = Twitter::postTweet($newTwitte);
        return $twitter->id_str;
	}
	
}
