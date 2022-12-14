<?php
/***********************************************************
listbanking.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 24, 2019
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
				<th title="Account Number"> Account Number </th>
				<th title="Bank Name"> Bank Name </th>
				<th title="Journal"> Journal </th>
				<th title="Chart Account"> Chart Account </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_bank_accounts  as $index => $ba_info)
                <tr  class="odd gradeX" data-ba_id="{{ $ba_info->ba_id }}">
                	<td><input type="checkbox" name="ck_ba_{{ $ba_info->ba_id }}" id="CK_BA_{{ $ba_info->ba_id }}" class="checkboxes" value="{{ $ba_info->ba_id }}" /></td>
                   <td>{{ $ba_info->ba_id }}</td>
                   <td>{{ $ba_info->ba_account_number }}</td>
                   <td>{{ $ba_info->ba_bank_name }}</td>
                   <td>{{ $journals_array[$ba_info->ba_accounting_journal]['aj_journal_label'] }}</td>
                   <td>{{ $accounts_array[$ba_info->ba_accounting_account]['aa_account'] . " - " . $accounts_array[$ba_info->ba_accounting_account]['aa_account_label'] }}</td>
                    <td><a href="#" data-ba_id="{{ $ba_info->ba_id }}" id="EDIT_ACCOUNT_{{ $ba_info->ba_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-ba_id="{{ $ba_info->ba_id }}"  id="DELETE_ACCOUNT_{{ $ba_info->ba_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>