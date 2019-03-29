<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Auth;
class GraphController extends Controller
{
    private $api;
   
    public function __construct()
    {

        $config = config('services.facebook');
        $fb = new Facebook([
            'app_id' => $config['cilent_id'],
            'app_secret' => $config['client_secret'],
            'default_graph_version'=> 'v2.6',
        ]);

        $this->api = $fb;
    }
 
    public function publishToPage($message, $imageName){
      
        try{
        
            $img = $this->api->fileToUpload(public_path().'\crisis\\'.$imageName);
            
           
            $post= $this->api->post('/'.env('FACEBOOK_PAGE_ID').'/photos',array('message' => $message, 'attached_media' => $img)
            ,env('FACEBOOK_ACCESS_TOKEN'));
           
            $post= $post->getGraphNode()->asArray();
           
            return $post['id'];
        }catch (FacebookSDKException $e){
            dd($e); // handle exception
        }
    }

    public function updatePost($message,$post_id,$imageName){
        try{
            if($imageName == null){ 
            $post= $this->api->post('/'.$post_id.'/comments',array('message' => $message)
            ,env('FACEBOOK_ACCESS_TOKEN'));

            $post= $post->getGraphNode()->asArray();
            }
            else{
                
                    $img = $this->api->fileToUpload(public_path().'\crisis\\'.$imageName);
            
                    $post= $this->api->post('/'.$post_id.'/comments',array('message' => $message,'attached_media'=>$img)
                    ,env('FACEBOOK_ACCESS_TOKEN'));
        
                    $post= $post->getGraphNode()->asArray();
                    

            }
            
        }catch (FacebookSDKException $e){
            dd($e); // handle exception
        }
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

}
