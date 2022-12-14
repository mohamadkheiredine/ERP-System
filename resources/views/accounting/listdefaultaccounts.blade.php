<?php
/***********************************************************
listdefaultaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List of default accounts
***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id" style="width:50px;white-space: nowrap;">ID</th>
				<th title="label">Label</th>
				<th title="Account">Account</th>
			</tr>
		</thead>
		<tbody>
	     @foreach ( $lst_default_accounts as $key => $account_info )
            <tr>
				<td>{{ $account_info->da_id }}</td> 
				<td>{{ $account_info->da_account_label }}</td>
				<td>
					<input type="hidden" name="da_id[]" value="{{ $account_info->da_id }}" />
					<select name="da_account_value[]" id="DA_ACCOUNT_VALUE_{{ $account_info->da_id }}" class="form-control">
						<option value="0">&nbsp;&nbsp;</option>
						@foreach ( $lst_accounts as $key => $acc_info )
						<option {{ $account_info->da_account_value == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label }}</option>
						@endforeach
					</select>
				</td>
			</tr>
	     @endforeach
		</tbody>
</table>