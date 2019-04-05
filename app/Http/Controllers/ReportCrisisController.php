<?php

namespace App\Http\Controllers;

use App\ReportCrisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Events\CrisisCreated;
use App\Crisis;

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
        //update
        $data = request()->all();

        $reportCrisis = ReportCrisis::whereId($id)->first();

        $user = new User();

        $data['id'] = $user->fetchUser()['id'];
        $data['date'] = $reportCrisis->date;
        $data['time'] = $reportCrisis['time'];
        $data['name'] = $reportCrisis['name'];
        $data['telephoneNumber'] = $reportCrisis['telephone_number'];
        $data['postalCode'] = $reportCrisis['postal_code'];
        $data['address'] = $reportCrisis['address'];
        $data['crisisType'] = $reportCrisis['crisis_type'];
        $data['radius'] = $reportCrisis['radius'];
        
        $imageName = null;

        if (array_key_exists('image', $reportCrisis)){        
            $imageName = str_random(40);
            $image = Image::make($reportCrisis['image']->getRealPath());
            // $image->resize(320, 240);
            $image->save('crisis/'.  $imageName . ".{$reportCrisis['image']->getClientOriginalExtension()}"); // Original Image
            $imageName = $imageName.".".$reportCrisis['image']->getClientOriginalExtension();
        }

        $data['image'] = $imageName;

        $crisis = Crisis::newCrisis($data);

        foreach($data['assistanceRequired'] as $assistance){
            $crisis->agency()->attach($assistance);
        }

        event(new CrisisCreated($crisis));

        $reportCrisis->delete();

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
