<?php
/***********************************************************
listtransmovements.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 7, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display List of Movement for the selected transaction
***********************************************************/

?>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th style="width:23%">Account Payable</th>
			<th style="width:23%">Account Receivable</th>
			<th style="width:30%">Label Operation</th>
			<th style="width:10%">Debit</th>
			<th style="width:10%">Credit</th>
			<th style="width:10%">Currency</th>
			<th style="width:2%">Edit</th>
			<th style="width:2%">Delete</th>
		</tr>
	</thead>
	<tbody id="TRANS_MOVEMENTS" >
		@foreach($lst_movements as $index => $movement_info )
		<?php 
		//if(!isset($accounts_array[ $movement_info->tm_ledger_account ]))
		//    continue;
		?>
		<tr>
			<td>{{ (  $movement_info->tm_ledger_account != '' && $movement_info->tm_ledger_account != 0 ) ? $accounts_array[ $movement_info->tm_ledger_account ]['aa_account_ref'] . " - " . $accounts_array[ $movement_info->tm_ledger_account ]['aa_account_label'] : "" }}</td>
			<td>{{ ( $movement_info->tm_sub_ledger_account != '' ) ? $accounts_array[$movement_info->tm_sub_ledger_account]['aa_account_ref'] . " - " . $accounts_array[$movement_info->tm_sub_ledger_account]['aa_account_label'] : "" }}</td>
			<td>{{ $movement_info->tm_ledger_label }}</td>
			<td>{{ number_format($movement_info->tm_debit,2) }}</td>
			<td>{{ number_format($movement_info->tm_credit,2) }}</td> 
			<td><b>{{ $movement_info->currency->cc_currency_code }}</b></td> 
			<td style="width:2px;"><a  data-tm_id="{{ $movement_info->tm_id }}"  href="#"  id="EDIT_MOV_{{ $movement_info->tm_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
            <td style="width:2px;"><a  data-tm_id="{{ $movement_info->tm_id }}" data-at_id="{{ $movement_info->fk_tran_id }}"  href="#"  id="DELETE_MOV_{{ $movement_info->tm_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
		</tr>
		@endforeach
	</tbody>
</table>