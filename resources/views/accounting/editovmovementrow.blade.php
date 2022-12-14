<?php
/***********************************************************
editmovementrow.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Movement Row 
***********************************************************/

?>

<td>
	 <select class="bs-select form-control row-select" name="tm_sub_ledger_account" id="" data-actions-box="true">
            <option value="0"> --Select Account--</option>
            @foreach ( $lst_accounts as $key => $account_info )
                    <option {{ $movement_info->tm_sub_ledger_account == $account_info->aa_id ? "selected" : "" }} value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref . " - " . $account_info->aa_account_label }}</option>
            @endforeach
    </select>
</td>
<td>
	<input type="text" name="tm_ledger_label" class="form-control" value="{{ $movement_info->tm_ledger_label }}" />
</td>
<td><input type="number" step="0.1" min="0" max="10000000" name="tm_debit" class="form-control" value="{{ $movement_info->tm_debit }}" /></td>
<td><input type="number" step="0.1" min="0" max="10000000" name="tm_credit" class="form-control" value="{{ $movement_info->tm_credit }}" /></td>
<td>
<select class="bs-select form-control" name="tm_currency_id" data-actions-box="true">
        <option value="0">Currency</option>
        @foreach ( $lst_currencies as $key => $curr_info )
                <option {{ $movement_info->tm_currency_id == $curr_info->cc_id ? "selected" : "" }} value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name  }}</option>
        @endforeach
</select>
</td>
<td colspan="2" >
	<input type="hidden"  name="tm_id" value="{{ $movement_info->tm_id }}" /><button type="button" class="btn btn-outline-info" name="btn_save_row" id="BTN_SAVE_ROW" >Save Info</button>
</td>