
<?php
    $time = date("h:i:s"); 
    $convertedTime = date('H:i:s',strtotime('+20 hour',strtotime($time))); 

    $dengue = $crises->where('crisis_type', "Dengue");
    $fire_outbreak = $crises->where('crisis_type', "Fire Outbreak");
    $gas_leak = $crises->where('crisis_type', "Gas Leak");

    $thirty_min_dengue =  $dengue->where("created_at", '>=', '2019-03-21 15:00:00');
    $thirty_min_fire_outbreak =  $fire_outbreak->where("created_at", '>=', '2019-03-21 15:00:00');
    $thirty_min_gas_leak =  $gas_leak->where("created_at", '>=', '2019-03-21 15:00:00');

    $twenty_four_hours_dengue =  $dengue->where("created_at", '>=', '2019-03-21 15:00:00');
    $twenty_four_hours_fire_outbreak =  $fire_outbreak->where("created_at", '>=', '2019-03-21 15:00:00');
    $twenty_four_hours_gas_leak =  $gas_leak->where("created_at", '>=', '2019-03-21 15:00:00');

    $one_week_dengue =  $dengue->where("created_at", '>=', '2019-03-21 15:00:00');
    $one_week_fire_outbreak =  $fire_outbreak->where("created_at", '>=', '2019-03-21 15:00:00');
    $one_week_gas_leak =  $gas_leak->where("created_at", '>=', '2019-03-21 15:00:00');

    $thirty_total = $thirty_min_dengue->count() + $thirty_min_fire_outbreak->count() + $thirty_min_gas_leak->count();
    $twenty_four_total = $twenty_four_hours_dengue->count() + $twenty_four_hours_fire_outbreak->count() + $twenty_four_hours_gas_leak->count();
    $one_week_total = $one_week_dengue->count() + $one_week_fire_outbreak->count() + $one_week_gas_leak->count();

    $fireAddr =  $dengue->where("created_at", '>=', '2019-03-21 15:00:00');
    $firePostal =  $fire_outbreak->where("created_at", '>=', '2019-03-21 15:00:00');
    $thirty_min_gas_leak =  $gas_leak->where("created_at", '>=', '2019-03-21 15:00:00');
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
   Report generated on {{$convertedTime}} {{date("d/m/Y")}}
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
            <td>{{$twenty_four_hours_fire_outbreak->count()}}</td>
            <td>{{$twenty_four_hours_gas_leak->count()}}</td>
            <td>{{$twenty_four_hours_dengue->count()}}</td>
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
        <tr>
            <th>No.</th>
            <th>Address</th>
            <th>Postal Code</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <tr>
            <td>30-minutes</td>
            <td>{{$twenty_four_hours_fire_outbreak->pluck('address')}}</td>
            <td>{{$thirty_min_fire_outbreak->count()}}</td>
            <td>{{$thirty_min_gas_leak->count()}}</td>
            <td>{{$thirty_min_dengue->count()}}</td>
        </tr>
        <tr>
            <td>24-hours</td>
            <td>{{$twenty_four_total}}</td>
            <td>{{$twenty_four_hours_fire_outbreak->count()}}</td>
            <td>{{$twenty_four_hours_gas_leak->count()}}</td>
            <td>{{$twenty_four_hours_dengue->count()}}</td>
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
{{-- line chart --}}
{{-- line chart --}}
{{-- Crisis type, affected area --}}
{{-- pie chart %fire %gas %dengue --}}
{{-- status chart today --}}

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
        
       