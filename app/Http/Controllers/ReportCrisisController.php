<?php

namespace App\Http\Controllers;

use App\ReportCrisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Events\ReportCrisisCreated;
use App\Events\ReportCrisisUpdated;

class ReportCrisisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reportCrisis = ReportCrisis::with('user:id,name')->get();

        return response()->json([
            'report_crises' => $reportCrisis,
        ], 200);
    }

    /**
     * Display a listing of the crisis which had been archived.
     *
     * @return \Illuminate\Http\Response
    */
    public function archive()
    {
        $reportCrisis = ReportCrisis::onlyTrashed()->with('user:id,name')->get();

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

        $user = new User();

        $data['id'] = $user->fetchUser()['id'];
        $data['date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $data['date']);
        $data['time'] = \Carbon\Carbon::createFromFormat('g:i A', $data['time']);
     
        $reportCrisis = ReportCrisis::newCrisis($data);

        $assistances = explode(',', $data['assistanceRequired']);

        foreach($assistances as $assistance){
            $reportCrisis->agency()->attach($assistance);
        }

        event(new ReportCrisisCreated($reportCrisis));

        return response()->json([
            'message' => 'You have successfully registered a new crisis!',
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

        $reportCrisis->update([
            'description' => $data['description'],
            'status' => $data['status']
        ]);

        event(new ReportCrisisUpdated($reportCrisis));

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function dengueCrisis()
    {
        $reportCrisis = ReportCrisis::where('crisis_type', 'Dengue')->get();

        return response()->json([
            'report_crises' => $reportCrisis,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function fireCrisis()
    {
        $reportCrisis = ReportCrisis::where('crisis_type', 'Fire Outbreak')->get();

        return response()->json([
            'report_crises' => $reportCrisis,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function gasLeakCrisis()
    {
        $reportCrisis = ReportCrisis::where('crisis_type', 'Gas Leak')->get();

        return response()->json([
            'report_crises' => $reportCrisis,
        ], 200);
    }
}
