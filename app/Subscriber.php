<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [ 'name', 'telephone_number', 'email'];

    // Add your validation rules here
    public static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:subscribers',
        'telephone_number' => 'bail|required|digits:8',
    ];

    public static function newSubscription($data){

        Subscriber::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'telephone_number' => $data['telephone_number'],
        ]);
    }




}
