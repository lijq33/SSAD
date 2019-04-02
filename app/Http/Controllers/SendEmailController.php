<?php

namespace App\Http\Controllers;
use App\Mail\TeamTenReport;
use PDF;


use Illuminate\Http\Request;

class SendEmailController extends Controller
{
    public function send()
    {

        $crises = \App\Crisis::get();

        $data = [
            'crises' => $crises,
        ];

        $pdf = PDF::loadView('pdf.template', $data)->save(public_path().'/KeyIndicators.pdf');

        \Mail::to('itsjianhao@gmail.com')->send(new TeamTenReport());
    }
}
