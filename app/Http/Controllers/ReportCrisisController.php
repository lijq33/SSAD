<?php

namespace App\Http\Controllers;

use App\ReportCrisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Http\Controllers\CrisisController;

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
    public function update(Request $request, ReportCrisis $reportCrisis)
    {

        $crisisStore = new CrisisController();
        $crisisStore->store($request);

        $reportCrisis->delete();

        return response()->json([
            'message' => 'You have successfully updated a crisis!',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReportCrisis  $reportCrisis
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportCrisis $reportCrisis)
    {
        $reportCrisis->delete();

        return response()->json([
            'message' => 'Crisis has been archived'
        ]);
    }
}
