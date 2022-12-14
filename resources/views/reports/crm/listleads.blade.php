<?php
/***********************************************************
listleads.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
<table class="table table-striped m-table" style="width:100%;" >
		<thead>
			<tr>
				<th title="Id"> ID </th>
				<th title="Lead name"> Lead Name </th>
				<th title="Company"> Company </th>
				<th title="phone"> Phone </th>
				<th title="Mobile"> Mobile </th>
				<th title="Email"> Email </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($report_leads as $index => $lead_info)
                <tr  class="odd gradeX" data-cl_id="{{ $lead_info->cl_id }}">
                   <td>{{ $lead_info->cl_id }}</td>
                   <td>{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</td>
                   <td>{{ $lead_info->cl_company_name }}</td>
                   <td>{{ $lead_info->cl_phone }}</td>
                   <td>{{ $lead_info->cl_mobile }}</td>
                   <td>{{ $lead_info->cl_email }}</td> 
                </tr>
                @endforeach
		</tbody>
</table>