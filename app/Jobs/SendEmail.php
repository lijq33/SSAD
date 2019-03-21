<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\DataUpdate;
use Jimmyjs\ReportGenerator\Facades\PdfReport;


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
        $queryBuilder = \App\User::select(['name', 'email'])->get();
        $columns = ['name', 'email'];

        $pdf = PdfReport::of('1', '1', $queryBuilder, $columns)
         ->setPaper('a6')
         ->make();
         


        \Mail::to('test@testingemail.com')->send(new DataUpdate());
    }

    public function displayReport(Request $request) {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $sortBy = $request->input('sort_by');

        $title = 'Registered User Report'; // Report title

        $meta = [ // For displaying filters description on header
            'Registered on' => $fromDate . ' To ' . $toDate,
            'Sort By' => $sortBy
        ];

        $queryBuilder = User::select(['name', 'balance', 'registered_at']) // Do some querying..
                            ->whereBetween('registered_at', [$fromDate, $toDate])
                            ->orderBy($sortBy);

        $columns = [ // Set Column to be displayed
            'Name' => 'name',
            'Registered At', // if no column_name specified, this will automatically seach for snake_case of column name (will be registered_at) column from query result
            'Total Balance' => 'balance',
            'Status' => function($result) { // You can do if statement or any action do you want inside this closure
                return ($result->balance > 100000) ? 'Rich Man' : 'Normal Guy';
            }
        ];

        // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
        return PdfReport::of($title, $meta, $queryBuilder, $columns)
                        ->editColumn('Registered At', [ // Change column class or manipulate its data for displaying to report
                            'displayAs' => function($result) {
                                return $result->registered_at->format('d M Y');
                            },
                            'class' => 'left'
                        ])
                        ->editColumns(['Total Balance', 'Status'], [ // Mass edit column
                            'class' => 'right bold'
                        ])
                        ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                            'Total Balance' => 'point' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
                        ])
                        ->limit(20) // Limit record to be showed
                        ->stream(); // other available method: download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
    }
}
