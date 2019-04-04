<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Agency;
use Illuminate\Database\Eloquent\SoftDeletes;
use Map;
use Intervention\Image\Facades\Image;


class Crisis extends Model
{
    use SoftDeletes;

    protected $appends = ['lat', 'lng'];

    
    public function getLatAttribute(){
        $response = Map::findLocation($this->attributes['postal_code']);
        return $response['lat'];
    }

    
    public function getLngAttribute(){
        $response = Map::findLocation($this->attributes['postal_code']);
        return $response['lng'];
    }

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
        'crisisType' => 'required|in:Fire Outbreak,Dengue,Gas Leak',
        // 'image' => 'mimes:jpeg,bmp,png',
        'radius' => 'required_if:crisisType,Dengue'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['user_id', 'name', 'telephone_number', 'postal_code', 'date', 'time', 'address',
                            'crisis_type', 'status', 'description', 'image', 'facebook_post_id','twitter_post_id' , 'radius'];

    public static function newCrisis($data){

        $data['date'] = (Carbon::parse($data['date'])->format('Y/m/d'));
        $data['time'] = (Carbon::parse($data['time'])->format('H:i:s'));

        $imageName = null;

        if (array_key_exists('image', $data)){        
            $imageName = str_random(40);
            $image = Image::make($data['image']->getRealPath());
            // $image->resize(320, 240);
            $image->save(public_path('crisis\\') .  $imageName . ".{$data['image']->getClientOriginalExtension()}"); // Original Image
            $imageName = $imageName.".".$data['image']->getClientOriginalExtension();
        }

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
            'radius' => $data['radius'],
            'image' => $imageName
        ]);

        return $crisis;
    }

}
