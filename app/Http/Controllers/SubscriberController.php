<?php

namespace App\Http\Controllers;

use App\Subscriber;
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
                'message' => 'You are required to fill in all the fields'
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
    public function destroy($telephone_number)
    {

    }

}
