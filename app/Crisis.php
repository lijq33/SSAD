<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Crisis extends Model
{
   
    // Add your validation rules here
    public static $rules = [
        'name' => 'bail|required',
        'telephoneNumber' => 'bail|required|integer|digits:8',
        'postalCode' => 'bail|required|integer|digits:6',
        'date' => 'bail|required|date_format:d/m/Y|before:tomorrow',
        'time' => 'required',
        'address' => 'required',
        'crisisType' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [ 'user_id', 'name', 'telephone_number', 'postal_code', 'date', 'time', 'address', 
                            'assistance_required', 'crisis_type'];

    public static function newCrisis($data){

        $data['date'] = (Carbon::parse($data['date'])->format('Y/m/d'));
        $data['time'] = (Carbon::parse($data['time'])->format('H:i:s'));

        $assistance = implode(", ", $data['assistanceRequired']);

        Crisis::create([
            'name' => $data['name'],
            'user_id' => $data['id'],
            'telephone_number' => $data['telephoneNumber'],
            'postal_code' => $data['postalCode'],
            'address' => $data['address'],

            'crisis_type' => $data['crisisType'],
            'date' => $data['date'],
            'time' => $data['time'],
            'assistance_required' => $assistance,
        ]);
    }

}
