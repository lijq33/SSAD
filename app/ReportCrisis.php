<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ReportCrisis extends Model
{  
    // Add your validation rules here
    public static $rules = [
        'name' => 'bail|required',
        'telephoneNumber' => 'bail|required|integer|digits:8',
        // 'postalCode' => 'bail|required|integer|digits:6',
        'date' => 'bail|required|date_format:d/m/Y|before:tomorrow',
        'time' => 'required',
        'location' => 'required',
        'crisisType' => 'required|in:Fire Outbreak,Dengue,Gas Leak',
        'image' => 'mimes:jpeg,bmp,png'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = ['name', 'telephone_number', 'postal_code', 'date', 'time', 'address',
                            'crisis_type', 'status', 'description', 'image'];

    public static function newReportedCrisis($data){

        $data['date'] = (Carbon::parse($data['date'])->format('Y/m/d'));
        $data['time'] = (Carbon::parse($data['time'])->format('H:i:s'));

        $imageName = null;

        if ($data['image'] !== "null"){        
            $imageName = str_random(40);
            $image = Image::make($data['image']->getRealPath());
            // $image->resize(320, 240);
            $image->save(public_path('crisis\\') .  $imageName . ".{$data['image']->getClientOriginalExtension()}"); // Original Image
            $imageName = $imageName.".".$data['image']->getClientOriginalExtension();
        }

        $reportCrisis = ReportCrisis::create([
            'name' => $data['name'],
            'telephone_number' => $data['telephoneNumber'],
            'postal_code' => $data['postalCode'],
            //'postal_code' => '123123',
            'address' => $data['location'],
            
            'crisis_type' => $data['crisisType'],
            'date' => $data['date'],
            'time' => $data['time'],
            
            'status' => 'pending',
            'description' => $data['description'],
            'image' => $imageName
        ]);

        return $reportCrisis;
    }
}
