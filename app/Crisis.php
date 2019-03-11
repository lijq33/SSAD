<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

        // array(9) {
        //     ["name"]=>
        //     string(10) "Li JinQuan"
        //     ["telephoneNumber"]=>
        //     string(8) "12341212"
        //     ["date"]=>
        //     object(Carbon\Carbon)#749 (3) {
        //       ["date"]=>
        //       string(26) "2019-03-11 01:55:15.000000"
        //       ["timezone_type"]=>
        //       int(3)
        //       ["timezone"]=>
        //       string(3) "UTC"
        //     }
        //     ["time"]=>
        //     object(Carbon\Carbon)#752 (3) {
        //       ["date"]=>
        //       string(26) "2019-03-11 10:15:00.000000"
        //       ["timezone_type"]=>
        //       int(3)
        //       ["timezone"]=>
        //       string(3) "UTC"
        //     }
        //     ["address"]=>
        //     string(4) "1234"
        //     ["postalCode"]=>
        //     string(6) "123412"
        //     ["assistanceRequired"]=>
        //     array(4) {
        //       [0]=>
        //       string(19) "Emergency Ambulance"
        //       [1]=>
        //       string(13) "Fire-Fighting"
        //       [2]=>
        //       string(13) "Rescue & Evac"
        //       [3]=>
        //       string(16) "Gas Leak Control"
        //     }
        //   }

        Crisis::create([
            'user_id' => $data(['name']),
            'name' => $data(['name']),
            'telephone_number' => $data(['name']),
            'postal_code' => $data(['name']),
            'date' => $data(['name']),
            'time' => $data(['name']),
            'address' => $data(['name']),
            'crisis_type' => $data(['name']),
            'assistance_required' => $data(['name']),
        ]);
    }

}
