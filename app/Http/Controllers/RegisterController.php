<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
        $users = User::withTrashed()->get();

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
            'password' => array(
                        'bail',
                        'required',
                        'string',
                        'min:8',
                        'confirmed',
                        'max:30',
                        'regex:/^.*(?=.{5,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!@#$%^&*()]).*$/'
                    ),
            'email' => 'bail|required|string|email|max:255|unique:users',
            'name' => 'bail|required|string|max:255',
            'telephone_number' => 'bail|required|digits:8',
            'roles' => 'bail|required|in:CallCenterOperator,CrisisManager,AccountManager'
        ];
    
        $messages = [
            'unique' => 'This :attribute has been taken!',
            'required' => 'We need to know your :attribute!',
            'email' => 'The format of :attribute is incorrect!',
            'max' => 'Your :attribute is too long!',
            'digits:value' => 'Your :attribute should have :value digits',
            'regex' => 'The format of :attribute is wrong',
            'password.regex'  => 'Your password did not fulfill the minimum requirement',
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

        return response()->json([
            'message' => 'A new account has been successfully created'
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $user = User::onlyTrashed()->whereId($id)->first();
        
        if ($user !== null)
            $user->restore();

        return response()->json([
            'message' => 'This account has been successfully enabled!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'You have disable this account.',
        ], 200);
    }
}
