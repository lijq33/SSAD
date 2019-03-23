<?php

namespace App\Http\Controllers;

use App\Events\CrisisCreated;
use App\Subscriber;
use App\SMS;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
 /*
    |--------------------------------------------------------------------------
    | Subscriber Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the subscription of new users as well as their
    | creation.
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // public function index()
    // {
    //     $crisis = \App\Crisis::find(1)->first();

    //     event(new CrisisCreated($crisis));

    //     $subscribers = Subscriber::all();
        
    //     if($subscribers->isEmpty()){
    //         var_dump("empty");
    //         return;
    //     }


    //     foreach($subscribers as $subscriber){
    //         var_dump($subscriber->telephone_number);
    //         var_dump($subscriber->name);
    //         // $content = $event->crisis;
    //         // $sms->sendSMS($subscriber->telephone_number,  $content);
    //     }
    // }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function store(Request $request)
    {
        $data = request()->all();

        $validator = Validator::make($data = request()->all(), Subscriber::$rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        Subscriber::newSubscription($data);
        
        return response()->json([
            'message' => 'You have successfully subscribe to our services. You will be redirected shortly',
            'url' => $this->redirectTo
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data = request()->all();

        Subscriber::with('name', $data['name']) 
            ->with('telephone_number', $data['telephone_number'])
            ->with('email', $data['email']);
    }

}
