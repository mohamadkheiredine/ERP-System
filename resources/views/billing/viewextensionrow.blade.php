<?php
/***********************************************************
extensionrow.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 18, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :
Extension Row of tde Payment Voucher
***********************************************************/

?> 
	<td style="width:2px;">#</td>
	<td style="width:2px;"></td>
	<td>
	<input type="hidden" name="ve_id" value="{{ $voucher_extension->ve_id }}" />
		<select class="bs-select ExtensionAccount form-control" name="ve_extention_account_id" data-actions-box="true">
                <option value="">-- Select Account --</option>
                @foreach ( $lst_chart_accounts as $key => $ca_info )
                        <option {{ $voucher_extension->ve_extention_account_id == $ca_info->aa_id ? "selected" : "" }}  value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                @endforeach
        </select>
	</td>
	<td>
		<input type="number" name="ve_extension_amount" min="1" max="9999999" value="{{ $voucher_extension->ve_extension_amount }}" class="form-control" style="width:100%" />
	</td>
	<td>
		<select class="bs-select ExtensionCurrency form-control" name="ve_extension_currency" data-actions-box="true">
                <option value="">-- Select Currency --</option>
                @foreach ( $lst_currencies as $key => $currency_info )
                        <option {{ $voucher_extension->ve_extension_currency == $currency_info->cc_id ? "selected" : "" }}  value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                @endforeach
        </select>
	</td>
	<td><input type="text" name="ve_extension_notes"  class="form-control" value="{{ $voucher_extension->ve_extension_notes }}" style="width:100%" /></td>
	<td style="width:4px;white-space: nowrap;text-align: center" colspan="2">
		<button type="button" name="btn_save_extension_{{ $voucher_extension->ve_id }}" id="BTN_SAVE_EXTENSION_{{ $voucher_extension->ve_id }}" class="btn btn-info">Save</button>
	</td> 