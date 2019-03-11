<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Crisis;
use App\User;

class CrisisController extends Controller
{
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
     
        Crisis::newCrisis($data);

        // $appointment_class->sendSMS($telephone,  $content);
        // social media here? Or perhaps at event

        return response()->json([
            'message' => 'You have successfully registered a new crisis!',
        ], 200);
    }
}
