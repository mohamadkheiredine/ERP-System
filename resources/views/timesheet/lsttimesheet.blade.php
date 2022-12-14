<?php
/***********************************************************
lsttimesheet.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 5, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Table of list timesheet
***********************************************************/

?>

<table class="table m-table m-table--head-bg-success">
	<thead>
		<tr>
			<th style="width:10%">Date</th>
			<th style="width:20%">Day Type</th>
			<th style="width:20%">Checkin</th>
			<th style="width:20%">Checkout</th>
			<th style="width:10%">Overwork</th>
			<th style="width:20%"> hours </th>
		</tr>
	</thead>
	<tbody>
		
			<?php 
			foreach ( $timesheet_month_array as $date  => $record_info ) 
			{ 

			    $hours   = $timesheet_main_array[ $date]['hours'];
			    $mins    = $timesheet_main_array[ $date]['mins'];
			    $working_hours   = $timesheet_main_array[ $date]['day_type_hours'];
			    $total_hours     = $hours + $mins/60;
			    $over_work       = $total_hours - $working_hours;
			    
			    
			    $ow_hours = ceil($over_work);
			    $ow_min = $ow_hours - $over_work;
			    $min = $ow_min*60;
			     
			    for ($i = 0; $i < count($record_info['checkin']); $i++) 
			    {
			     ?>
			     	<tr bgcolor="{{ $timesheet_main_array[$date]['day_type_color'] }}">
        			    <td>{{ ($i == 0) ? $date : "" }}</td>
        			    <td>{{ $timesheet_main_array[$date]['day_type_name'] }}</td>
            			<td>{{ $record_info['checkin'][$i] }}</td>
            			<td>{{ $record_info['checkout'][$i] }}</td>
            			<td>{{ ( $i == ( count($record_info['checkin']) - 1 ) ) ? $ow_hours . " hours " . $min . " mins" : "" }}</td>
            			<td>{!! ($over_work < 0) ? "<span style='color:#ef5c74'>" : "" !!} {{ ( $i == ( count($record_info['checkin']) - 1 ) ) ?   $timesheet_main_array[ $date]['hours'] . " hours " . $timesheet_main_array[ $date]['mins'] . " mins"  : ""}} {!! ($over_work < 0) ? "</span>" : "" !!}  </td>
        			</tr>
			     <?php   
			    }
			}
			?> 
		
	</tbody>
</table>