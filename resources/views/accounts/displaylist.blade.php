<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="Client name"> Client Name </th>
				<th title="Company"> Company </th>
				<th title="Mobile"> Mobile </th>
				<th title="Email"> Email </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_accounts  as $index => $account_info)
                <tr  class="odd gradeX" data-ca_id="{{ $account_info->ca_id }}">
                	<td><input type="checkbox" name="ck_ca_{{ $account_info->ca_id }}" id="CK_CA_{{ $account_info->ca_id }}" class="checkboxes" value="{{ $account_info->ca_id }}" /></td>
                   <td>{{ $account_info->ca_id }}</td>
                   <td>{{ $account_info->ca_account_name }}</td>
                   <td>{{ $account_info->ca_company_name }}</td>
                   <td>{{ $account_info->ca_account_mobile }}</td>
                   <td>{{ $account_info->ca_account_email }}</td>
                    <td><a href="#" data-ca_id="{{  $account_info->ca_id }}" id="EDIT_ACCOUNT_{{  $account_info->ca_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-ca_id="{{  $account_info->ca_id }}"  id="DELETE_ACCOUNT_{{  $account_info->ca_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>