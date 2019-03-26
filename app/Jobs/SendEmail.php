<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\DataUpdate;
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
        
        //get data from database

        $crises = \App\Crisis::with('user:id,name')->get();

        $data = [
            'data'=> $crises,
        ];
        $pdf = PDF::loadView('pdf.template', $data)->save(public_path().'/KeyIndicators.pdf');

        \Mail::to('test@testingemail.com')->send(new DataUpdate());
    }
}
