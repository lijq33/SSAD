<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Crisis;
use App\User;
use App\Events\CrisisCreated;
use App\Events\CrisisUpdated;

class CrisisController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $crises = Crisis::with('user:id,name')->get();

        return response()->json([
            'crises' => $crises,
        ], 200);
    }

    /**
     * Display a listing of the crisis which had been archived.
     *
     * @return \Illuminate\Http\Response
    */
    public function archive()
    {
        $crises = Crisis::onlyTrashed()->with('user:id,name')->get();

        return response()->json([
            'crises' => $crises,
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

        $validator = Validator::make($data = request()->all(), Crisis::$rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new User();

        $data['id'] = $user->fetchUser()['id'];
        $data['date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date']);
        $data['time'] = \Carbon\Carbon::createFromFormat('g:i A', $data['time']);
     
        $crisis = Crisis::newCrisis($data);

        foreach($data['assistanceRequired'] as $assistance){
            $crisis->agency()->attach($assistance);
        }

        event(new CrisisCreated($crisis));

        return response()->json([
            'message' => 'You have successfully registered a new crisis!',
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crisis $crisis)
    {
        $data = request()->all();

        $rules = [
            'status' => 'required|string',
        ];

        $validator = Validator::make($data = request()->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $crisis->update([
            'description' => $data['description'],
            'status' => $data['status']
        ]);

        event(new CrisisUpdated($crisis));

        return response()->json([
            'message' => 'You have successfully updated a crisis!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function destroy(Crisis $crisis)
    {
        $crisis->delete();

        return response()->json([
            'message' => 'Crisis has been archived'
        ]);
    }
}
