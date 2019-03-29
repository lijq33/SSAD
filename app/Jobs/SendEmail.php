<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\TeamTenReport;
use PDF;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //key indicators and trends
        // generate graph 
        // graph 1 
        //y = total number of crisis 
        //x = time
        //graph 2

        $crises = \App\Crisis::get();

        $data = [
            'crises' => $crises,
        ];

        $pdf = PDF::loadView('pdf.template', $data)->save(public_path().'/KeyIndicators.pdf');

        \Mail::to('pm@primeminister.com')->send(new TeamTenReport());
    }
}
