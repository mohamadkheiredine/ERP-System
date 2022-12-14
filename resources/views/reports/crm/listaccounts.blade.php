<?php
/***********************************************************
listaccounts.blade.php
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
				<th title="Client name"> Client Name </th>
				<th title="Company"> Company </th>
				<th title="Mobile"> Mobile </th>
				<th title="Email"> Email </th> 
			</tr>
		</thead>
		<tbody>
			  	@foreach($report_accounts as $index => $account_info)
                <tr  class="odd gradeX" data-ca_id="{{ $account_info->ca_id }}">
                   <td>{{ $account_info->ca_id }}</td>
                   <td>{{ $account_info->ca_account_name }}</td>
                   <td>{{ $account_info->ca_company_name }}</td>
                   <td>{{ $account_info->ca_account_mobile }}</td>
                   <td>{{ $account_info->ca_account_email }}</td> 
                </tr>
                @endforeach
		</tbody>
</table>