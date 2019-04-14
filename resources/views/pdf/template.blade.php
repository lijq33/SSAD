
<?php
    $fireNo = 1;
    $dengueNo = 1;
    $gasLeakNo = 1;
    $date = date("Y-m-d");
    $minus1Week = date('Y-m-d',strtotime('-1 week',strtotime($date))); 

    $time = date("H:i:s");
    $minus30mins = date("H:i:s",strtotime('-30 minutes',strtotime($time)));

    $dengue = $crises->where('crisis_type', "Dengue");
    $fire_outbreak = $crises->where('crisis_type', "Fire Outbreak");
    $gas_leak = $crises->where('crisis_type', "Gas Leak");

    $dengueToday = $crises->where("date", '==', $date);
    $fireToday = $crises->where("date", '==', $date);
    $gasLeakToday = $crises->where("date", '==', $date);

    $thirty_min_dengue_before =  $dengueToday->where("time", '>=', $date.' '.$minus30mins);
    $thirty_min_dengue =  $thirty_min_dengue_before->where("created_at", '<=', $date.' '.$time);

    $thirty_min_fire_outbreak_before =  $fireToday->where("created_at", '>=', $date.' '.$minus30mins);
    $thirty_min_fire_outbreak =  $thirty_min_fire_outbreak_before->where("created_at", '<=', $date.' '.$time);

    $thirty_min_gas_leak_before =  $gasLeakToday->where("created_at", '>=', $date.' '.$minus30mins);
    $thirty_min_gas_leak =  $thirty_min_gas_leak_before->where("created_at", '<=', $date.' '.$time);

    $one_week_dengue_before =  $dengue->where("date", '>=', $minus1Week);
    $one_week_dengue =  $one_week_dengue_before->where("date", '<=', $date);

    $one_week_fire_outbreak_before =  $fire_outbreak->where("date", '>=', $minus1Week);
    $one_week_fire_outbreak =  $one_week_fire_outbreak_before->where("date", '<=', $date);

    $one_week_gas_leak_before =  $gas_leak->where("date", '>=', $minus1Week);
    $one_week_gas_leak =  $one_week_gas_leak_before->where("date", '<=', $date);

    $thirty_total = $thirty_min_dengue->count() + $thirty_min_fire_outbreak->count() + $thirty_min_gas_leak->count();
    $twenty_four_total = $dengueToday->count() + $fireToday->count() + $gasLeakToday->count();
    $one_week_total = $one_week_dengue->count() + $one_week_fire_outbreak->count() + $one_week_gas_leak->count();
?>
{{-- 
$startTime = date("Y-m-d H:i:s");

//display the starting time
echo 'Starting Time: '.$startTime;

//add 1 hour to time
$cenvertedTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($startTime)));

//display the converted time
echo 'Converted Time (added 1 hour): '.$cenvertedTime;

//add 1 hour and 30 minutes to time
$cenvertedTime = date('Y-m-d H:i:s',strtotime('+1 hour +30 minutes',strtotime($startTime)));

//display the converted time
echo 'Converted Time (added 1 hour & 30 minutes): '.$cenvertedTime;

//add 1 hour, 30 minutes and 45 seconds to time
$cenvertedTime = date('Y-m-d H:i:s',strtotime('+1 hour +30 minutes +45 seconds',strtotime($startTime)));

//display the converted time
echo 'Converted Time (added 1 hour, 30 minutes  & 45 seconds): '.$cenvertedTime;

//add 1 day, 1 hour, 30 minutes and 45 seconds to time
$cenvertedTime = date('Y-m-d H:i:s',strtotime('+1 day +1 hour +30 minutes +45 seconds',strtotime($startTime)));

//display the converted time
echo 'Converted Time (added 1 day, 1 hour, 30 minutes  & 45 seconds): '.$cenvertedTime; --}}


{{-- Header --}}
<div style="">
   <span style="align-items: center; font-style: italic; text-transform: uppercase; font-size: 1.125rem;">TEAM </span> 
   <img src = "assets\img\TL.jpg" style="width:50px;height:50px;"/> 
   Report generated on {{date("H:i:s")}} {{date("d/m/Y")}}
</div>
{{-- Header --}}


{{-- Page 1 Table--}}
<div>
    <h1 style="justify-content: center;"> Key Indicators and Trends Report </h1>

    Incident Summary (Occurance)
    <table>
        <tr>
            <th>Past Trends</th>
            <th>Total Number Of Crisis</th>
            <th>Fire Outbreak</th>
            <th>Gas Leak</th>
            <th>Dengue</th>
        </tr>
        <tr>
            <td>30-minutes</td>
            <td>{{$thirty_total}}</td>
            <td>{{$thirty_min_fire_outbreak->count()}}</td>
            <td>{{$thirty_min_gas_leak->count()}}</td>
            <td>{{$thirty_min_dengue->count()}}</td>
        </tr>
        <tr>
            <td>24-hours</td>
            <td>{{$twenty_four_total}}</td>
            <td>{{$fireToday->count()}}</td>
            <td>{{$gasLeakToday->count()}}</td>
            <td>{{$dengueToday->count()}}</td>
        </tr>
        <tr>
            <td>Week</td>
            <td>{{$one_week_total}}</td>
            <td>{{$one_week_fire_outbreak->count()}}</td>
            <td>{{$one_week_gas_leak->count()}}</td>
            <td>{{$one_week_dengue->count()}}</td>
        </tr>
    </table>
</div>
<br/>
<div>
    Fire Outbreak Incident Breakdown
    <table>
        @if ($fire_outbreak->count() != 0)
            <tr>
                <th>No.</th>
                <th>Address</th>
                <th>Postal Code</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        @endif
        @forelse($fire_outbreak as $fireEvent)
            <tr>
                <td>{{$fireNo++}}</td>
                <td>{{$fireEvent->address}}</td>
                <td>{{$fireEvent->postal_code}}</td>
                <td>{{$fireEvent->date}}</td>
                <td>{{$fireEvent->time}}</td>
            </tr>
        @empty
            <b>No Fire Outbreak event was reported today.</b>
        @endforelse
    </table>
</div>
<br/>
<div>
    Gas Leak Incident Breakdown
    <table>
        @if ($gas_leak->count() != 0)
            <tr>
                <th>No.</th>
                <th>Address</th>
                <th>Postal Code</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        @endif
        @forelse($gas_leak as $gasLeakEvent)
            <tr>
                <td>{{$gasLeakNo++}}</td>
                <td>{{$gasLeakEvent->address}}</td>
                <td>{{$gasLeakEvent->postal_code}}</td>
                <td>{{$gasLeakEvent->date}}</td>
                <td>{{$gasLeakEvent->time}}</td>
            </tr>
        @empty
            <b>No Gas Leak event was reported today.</b>
        @endforelse
    </table>
</div>
<br/>
<div>
    Dengue Incident Breakdown
    <table>
        @if ($dengue->count() != 0)
            <tr>
                <th>No.</th>
                <th>Address</th>
                <th>Postal Code</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        @endif
        @forelse($dengue as $dengueEvent)
            <tr>
                <td>{{$dengueNo++}}</td>
                <td>{{$dengueEvent->address}}</td>
                <td>{{$dengueEvent->postal_code}}</td>
                <td>{{$dengueEvent->date}}</td>
                <td>{{$dengueEvent->time}}</td>
            </tr>
        @empty
            <b>No Dengue event was reported today.</b>
        @endforelse
    </table>
</div>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        text-align: left;
        padding: 8px;
    }
    
    tr:nth-child(even) {background-color: #f2f2f2;}
</style>
        
       