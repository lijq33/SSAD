<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\DataUpdate;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'users' => $users,
        ], 200);
    }




    /**
     * Create a new user instance after a valid registration.
     *
     * @param  User  $data
     * @return \App\User
     */
    protected function store(User $request)
    {
        $data = request()->all();

        $rules = [
            'nric' => array(
                        'bail', 
                        'required',
                        'unique:users',
                        'size:9',
                        'regex:/[a-zA-Z]\d{7}[a-zA-Z]/u',
                        'string',
                    ),
            'email' => 'bail|required|string|email|max:255|unique:users',
            'name' => 'bail|required|string|max:255',
            'password' => 'bail|required|string|min:6|confirmed|max:30',
            'telephone_number' => 'bail|required|digits:8',
            'roles' => 'bail|required'
        ];
    
        $messages = [
            'unique' => 'This :attribute has been taken!',
            'required' => 'We need to know your :attribute!',
            'email' => 'The format of :attribute is incorrect!',
            'max' => 'Your :attribute is too long!',
            'digits:value' => 'Your :attribute should have :value digits',
            'regex' => 'The format of :attribute is wrong'
        ];
    
        $validator = Validator::make($data = request()->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'nric' => $data['nric'],
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'telephone_number' => $data['telephone_number'],
            'roles' => $data['roles'],
        ]);

        \Mail::to($user)->send(new DataUpdate($user));

        return response()->json([
            'message' => 'A new account has been successfully created'
        ], 200);
    }






}
