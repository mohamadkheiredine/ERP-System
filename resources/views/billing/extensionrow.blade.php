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

<tr>
	<td style="width:2px;">#</td>
	<td style="width:2px;"></td>
	<td>
		<select class="bs-select ExtensionAccount form-control" name="pv_extension_account[]" data-actions-box="true">
                <option value="">-- Select Account --</option>
                @foreach ( $lst_chart_accounts as $key => $ca_info )
                        <option value="{{ $ca_info->aa_id }}">{{ $ca_info->aa_account . " - "  . $ca_info->aa_account_label }}</option>
                @endforeach
        </select>
	</td>
	<td>
		<input type="number" name="pv_extension_value[]" min="1" max="9999999" value="1" class="form-control" style="width:100%" />
	</td>
	<td>
		<select class="bs-select ExtensionCurrency form-control" name="pv_ext_currency_id[]" data-actions-box="true">
                <option value="">-- Select Currency --</option>
                @foreach ( $lst_currencies as $key => $currency_info )
                        <option  value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                @endforeach
        </select>
	</td>
	<td><input type="text" name="pv_extension_notes[]"  class="form-control" style="width:100%" /></td>
	<td style="width:4px;white-space: nowrap;text-align: center"></td>
	<td style="width:4px;white-space: nowrap;text-align: center"><a href="#" class="DeleteExtRow" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>