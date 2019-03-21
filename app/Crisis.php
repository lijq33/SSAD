<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Agency;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crisis extends Model
{
    use SoftDeletes;

    /**
     * Get the user that submitted the crisis.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the agency which assistance will be required.
     */
    public function agency()
    {
        return $this->belongsToMany(Agency::class, 'crisis_agencies', 'crisis_id', 'agency_id');
    }

   
    // Add your validation rules here
    public static $rules = [
        'name' => 'bail|required',
        'telephoneNumber' => 'bail|required|integer|digits:8',
        'postalCode' => 'bail|required|integer|digits:6',
        'date' => 'bail|required|date_format:d/m/Y|before:tomorrow',
        'time' => 'required',
        'address' => 'required',
        'crisisType' => 'required', //add in for Enum
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['user_id', 'name', 'telephone_number', 'postal_code', 'date', 'time', 'address', 
                            'crisis_type', 'status', 'description', 'twitter_post_id', 'facebook_post_id'];

    public static function newCrisis($data){

        $data['date'] = (Carbon::parse($data['date'])->format('Y/m/d'));
        $data['time'] = (Carbon::parse($data['time'])->format('H:i:s'));

        $crisis = Crisis::create([
            'name' => $data['name'],
            'user_id' => $data['id'],
            'telephone_number' => $data['telephoneNumber'],
            'postal_code' => $data['postalCode'],
            'address' => $data['address'],
            
            'crisis_type' => $data['crisisType'],
            'date' => $data['date'],
            'time' => $data['time'],
            
            'status' => 'registered',
            'description' => $data['description'],
        ]);

        return $crisis;
    }

}
