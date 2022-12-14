<?php
/***********************************************************
listrates.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="Source Currency"> Source Currency </th>
				<th title="Destination Currency"> Destination Currency </th>
				<th title="Rate"> Rate </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lstexchange_rates  as $index => $exrate_info)
                <tr  class="odd gradeX" data-er_id="{{ $exrate_info->er_id }}">
                	<td><input type="checkbox" name="ck_er_{{ $exrate_info->er_id }}" id="CK_ER_{{ $exrate_info->er_id }}" class="checkboxes" value="{{ $exrate_info->er_id }}" /></td>
                   <td>{{ $exrate_info->er_id }}</td>
                   <td>{{ $currencies_array[ $exrate_info->er_from_currency ]['cc_currency_code'] . " - " . $currencies_array[ $exrate_info->er_from_currency ]['cc_currency_name'] }}</td>
                   <td>{{ $currencies_array[ $exrate_info->er_to_currency ]['cc_currency_code'] . " - " . $currencies_array[ $exrate_info->er_to_currency ]['cc_currency_name'] }}</td>
                   <td>{{ $exrate_info->er_exchange_rate }}</td>
                    <td><a href="#" data-er_id="{{ $exrate_info->er_id }}" id="EDIT_RATE_{{ $exrate_info->er_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-er_id="{{ $exrate_info->er_id }}"  id="DELETE_RATE_{{ $exrate_info->er_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>