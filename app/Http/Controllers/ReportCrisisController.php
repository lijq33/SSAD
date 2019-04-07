<?php

namespace App\Http\Controllers;

use App\ReportCrisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Events\CrisisCreated;
use App\Crisis;
use Intervention\Image\Facades\Image;

class ReportCrisisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reportCrisis = ReportCrisis::get();

        return response()->json([
            'report_crises' => $reportCrisis,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->all();

        $validator = Validator::make($data = request()->all(), ReportCrisis::$rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date']);
        $data['time'] = \Carbon\Carbon::createFromFormat('g:i A', $data['time']);
     
        $reportCrisis = ReportCrisis::newReportedCrisis($data);

        return response()->json([
            'message' => 'You have successfully reported a crisis! Thank you for your time! ',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReportCrisis  $reportCrisis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = request()->all();

        $reportCrisis = ReportCrisis::where('id', $id)->first();

        $user = new User();
        $data['id'] = $user->fetchUser()['id'];
        
        $data['date'] = $reportCrisis['date'];
        $data['time'] = $reportCrisis['time'];
        $data['name'] = $reportCrisis['name'];
        $data['telephoneNumber'] = $reportCrisis['telephone_number'];
        $data['postalCode'] = $reportCrisis['postal_code'];
        $data['address'] = $reportCrisis['address'];
        $data['crisisType'] = $reportCrisis['crisis_type'];
        $data['radius'] = $reportCrisis['radius'];

        if (!array_key_exists('description', $data)){        
            $data['description'] = $reportCrisis['description'];
        }

        $imageName = null;

        if (array_key_exists('image', $reportCrisis)){
            $imageName = $reportCrisis['image'];
        }
        
        if (array_key_exists('image', $data)){        
            $imageName = str_random(40);
            $image = Image::make($data['image']->getRealPath());
            $image->save('crisis/'.  $imageName . ".{$data['image']->getClientOriginalExtension()}"); // Original Image
            $imageName = $imageName.".".$data['image']->getClientOriginalExtension();
        }
        $data['image'] = $imageName;

        $crisis = Crisis::newCrisis($data);

        $assistances = explode(',', $data['assistanceRequired']);

        if($assistances[0] !== ""){
            foreach($assistances as $assistance){
                $crisis->agency()->attach($assistance);
            }
        }

        $reportCrisis->delete();

        event(new CrisisCreated($crisis));

        return response()->json([
            'message' => 'You have successfully registered a new crisis!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReportCrisis  $reportCrisis
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reportCrisis = ReportCrisis::whereId($id)->first(); 
        $reportCrisis->delete();

        return response()->json([
            'message' => 'Crisis has been rejected'
        ]);
    }
}
