<?php
/***********************************************************
listopeningvouchermovements.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 25, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/


?>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th style="width:23%">Account</th>
			<th style="width:30%">Label Operation</th>
			<th style="width:18%">Debit</th> 
			<th style="width:18%">Credit</th> 
			<th style="width:20%">Currency</th>
			<th style="width:2%">Edit</th> 
			<td style="width:2px;"></td>
		</tr>
	</thead>
	<tbody id="TRANS_MOVEMENTS" >
		@foreach($lst_movements as $index => $movement_info )
		<?php 
		//if(!isset($accounts_array[ $movement_info->tm_ledger_account ]))
		//    continue;
		?>
		<tr>
			<td>{{ ( $movement_info->tm_sub_ledger_account != '' && isset( $accounts_array[$movement_info->tm_sub_ledger_account]) ) ? $accounts_array[$movement_info->tm_sub_ledger_account]['aa_account_ref'] . " - " . $accounts_array[$movement_info->tm_sub_ledger_account]['aa_account_label'] : "" }}</td>
			<td>{{ $movement_info->tm_ledger_label }}</td>
			<td>{{ number_format($movement_info->tm_debit,2) }}</td>
			<td>{{ number_format($movement_info->tm_credit,2) }}</td>
			<td><b>{{ $movement_info->currency->cc_currency_code }}</b></td> 
			<td style="width:2px;"><a  data-tm_id="{{ $movement_info->tm_id }}"  href="#"  id="EDIT_MOV_{{ $movement_info->tm_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
		</tr>
		@endforeach
	</tbody>
</table>