<?php
/***********************************************************
listledger.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display List of ledger ( transaction and movememnt) Saved in the database
***********************************************************/

 
?>
  @foreach ($lst_transaction_movements as $trans_id => $movements_array )
 	@for( $i = 0;$i<count($movements_array);$i++ )
 		<?php 
     		$ledger_account     = $movements_array[$i]['ledger_account'];
     		$sub_ledger_account = $movements_array[$i]['sub_ledger_account'];
     		$account_label      = "";
     		$sub_account_label  = "";
     		if($ledger_account != 0)
     		{
     		    $account_label = isset($accounts_array[ $ledger_account]) ? $accounts_array[ $ledger_account]['aa_account_label'] : "N/A";
     		}
     		
     		if($sub_ledger_account != 0)
     		{
     		    $sub_account_label = isset($accounts_array[ $sub_ledger_account ]) ? $accounts_array[ $sub_ledger_account ]['aa_account_label'] : "N/A";
     		}
 		?>
 		<tr>
			<td><a  data-at_id="{{ $trans_id }}"  href="#"  id="EDIT_TRANS_{{ $trans_id }}" >{{ $transactions_array[ $trans_id ]['at_accounting_doc'] }}</a></td>
			<td>{{ $movements_array[$i]['transaction_date'] }}</td>
			<td>{{ $movements_array[$i]['accounting_doc'] }}</td>
			<td>{{ $account_label }}</td> 
			<td>{{ $movements_array[$i]['ledger_label'] }}</td>
			<td>{{ number_format($movements_array[$i]['debit'],2) }}</td>
			<td>{{ number_format($movements_array[$i]['credit'],2) }}</td>
			<td>{{ $movements_array[$i]['currency'] }}</td> 
			<td style="width:2px;"><a  data-tm_id="{{ $movements_array[$i]['tm_id'] }}" data-at_id="{{ $trans_id }}"   href="#"  id="EDIT_TRANS_{{ $trans_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
            <td style="width:2px;"><a  data-tm_id="{{ $movements_array[$i]['tm_id'] }}" data-at_id="{{ $trans_id }}"  href="#"  id="DELETE_MOVEMENTS_{{ $movements_array[$i]['tm_id'] }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
		</tr>
 	@endfor
 @endforeach