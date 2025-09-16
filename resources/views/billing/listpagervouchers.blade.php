<?php
/***********************************************************
listvouchers.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 21, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@foreach($lst_vouchers as $index => $voucher_info)
<tr  class="odd gradeX" data-pv_id="{{ $voucher_info->pv_id }}">
	<td><input type="checkbox" name="pv_checkbox_{{ $voucher_info->pv_id }}" id="PV_CHECKBOX_{{ $voucher_info->pv_id }}" class="checkboxes" value="{{ $voucher_info->pv_id }}" /></td>
   <td>{{ $voucher_info->pv_id }}</td>
   <td>{{ $voucher_info->pv_creation_date }}</td>
   <td>{{ $voucher_info->pv_code }}</td>
   <td>{{ isset($accounts_array[ $voucher_info->pv_account_payable ]) ? $accounts_array[ $voucher_info->pv_account_payable ]['aa_account_label'] : "-" }}</td>
   <td>{{ isset($accounts_array[ $voucher_info->pv_account_receivable ]) ? $accounts_array[ $voucher_info->pv_account_receivable ]['aa_account_label'] : "-" }}</td>
   <td>{{ number_format( $voucher_info->pv_payment_amount + $voucher_info->pv_extra_amount , 2 ) }}</td>
   <td><b>{{ $currency_array[ $voucher_info->pv_currency_id ]['cc_currency_code'] }}</b></td>
</tr>
@endforeach
