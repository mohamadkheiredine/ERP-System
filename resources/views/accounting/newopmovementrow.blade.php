<?php
/***********************************************************
newmovementrow.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 7, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<tr>
	<td>
		 <select class="bs-select form-control row-select SubLedgerAccount" name="tm_sub_ledger_account[]" id="" data-actions-box="true">
                <option value="0"> --Select Account--</option>
                @foreach ( $lst_accounts as $key => $account_info )
                        <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
                @endforeach
        </select>
	</td>
	<td>
		<input type="text" name="tm_ledger_label[]" class="form-control" value="" />
	</td>
	<td><input type="number" step="0.1" min="0" max="100000000" name="tm_debit[]" class="form-control" value="0.0" /></td> 
	<td><input type="number" step="0.1" min="0" max="100000000" name="tm_credit[]" class="form-control" value="0.0" /></td> 
	<td>
	 <select class="bs-select form-control" name="tm_currency_id[]" data-actions-box="true">
                <option value="0">Currency</option>
                @foreach ( $lst_currencies as $key => $curr_info )
                        <option value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name  }}</option>
                @endforeach
        </select>
	</td>
	<td style="width:2px;"></td>
    <td style="width:2px;"><a  href="#"  class="RemoveMov" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>