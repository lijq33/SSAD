<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Auth;
class GraphController extends Controller
{
    private $api;
   
    public function __construct(/*Facebook $fb*/)
    {
     
            $config = config('services.facebook');
            $fb = new Facebook([
                'app_id' => '610989759339386',
                'app_secret' =>'9900d6676b7faad607a63cdb60518f14',
                'default_graph_version' => 'v2.6',
            ]);
        

        $this->api = $fb;
       
    }
 
    public function retrieveUserProfile(){
        try {
 
            $params = "first_name,last_name,age_range,gender";
 
            $user = $this->api->get('/me?fields='.$params)->getGraphUser();
 
            dd($user);
 
        } catch (FacebookSDKException $e) {
 
        }
 
    }
        
    public function publishToProfile(Request $request){
        try {
            $response = $this->api->post('/me/feed', [
                'message' => $request->message
            ])->getGraphNode()->asArray();
            if($response['id']){
            // post created
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
    public function getPageAccessToken($page_id){
        try {
             // Get the \Facebook\GraphNodes\GraphUser object for the current user.
             // If you provided a 'default_access_token', the '{access-token}' is optional.
             $response = $this->api->get('/me/accounts', Auth::user()->token);
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    
        try {
            $pages = $response->getGraphEdge()->asArray();
            foreach ($pages as $key) {
                if ($key['id'] == $page_id) {
                    return $key['access_token'];
                }
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function publishToPage($message){
        $page_id='342236433072588';
       

        try{
            $post= $this->api->post('/'.$page_id.'/feed',array('message' => $message)
            ,'EAAIrsSORG3oBAFlRZANTDB8svZB9naEZCNehPLJBkxUbQ20S1FhGIbb17k48vHZCPIOylTWb3w
            9hqTbhfDZBfCZCKkjlLYZBElPJmVuNcWyGq56vlNfEv0qz9q4M9ZAMZAn0p49mxxEGFOLBbcBJKxx9GieYsKqFz4KZA9odMTsHNzAgZDZD');
           

            $post= $post->getGraphNode()->asArray();
           
            dd($post);
            return $post['id'];
        }catch (FacebookSDKException $e){
            dd($e); // handle exception
        }
    }

    public function updatePost($message,$post_id){
        $page_id='342236433072588';
        

        try{
            $post= $this->api->post('/'.$page_id.'_'.$post_id,array('message' => $message)
            ,'EAAIrsSORG3oBAFlRZANTDB8svZB9naEZCNehPLJBkxUbQ20S1FhGIbb17k48vHZCPIOylTWb3w
            9hqTbhfDZBfCZCKkjlLYZBElPJmVuNcWyGq56vlNfEv0qz9q4M9ZAMZAn0p49mxxEGFOLBbcBJKxx9GieYsKqFz4KZA9odMTsHNzAgZDZD');
           

            $post= $post->getGraphNode()->asArray();
            
            dd($post);
            
        }catch (FacebookSDKException $e){
            dd($e); // handle exception
        }
    }


}
