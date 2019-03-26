
<?php
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

   Report generated on {{date("h:i:s")}} {{date("d/m/Y")}}
</div>
{{-- Header --}}


{{-- Page 1 Table--}}
<div>
    <h1 style="justify-content: center;"> Key Indicator and Trend Report </h1>

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
                <td>{{$total}}</td>
                <td>{{$dengue_count}}</td>
                <td>{{$fire_outbreak_count}}</td>
                <td>{{$gas_leak_count}}</td>
            </tr>
            <tr>
                <td>24-hours</td>
                <td>{{$total}}</td>
                <td>{{$dengue_count}}</td>
                <td>{{$fire_outbreak_count}}</td>
                <td>{{$gas_leak_count}}</td>
            </tr>
            <tr>
                <td>Week</td>
                <td>{{$total}}</td>
                <td>{{$dengue_count}}</td>
                <td>{{$fire_outbreak_count}}</td>
                <td>{{$gas_leak_count}}</td>
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
        
       