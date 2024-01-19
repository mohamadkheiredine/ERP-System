<?php
/***********************************************************
lstjournalvouchers.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 22, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/

?>


@foreach($lst_journal_vouchers as $index => $voucher_info)
<tr  class="odd gradeX" data-pj_id="{{ $voucher_info->pj_id }}">
	<td><input type="checkbox" name="pj_checkbox_{{ $voucher_info->pj_id }}" id="PJ_CHECKBOX_{{ $voucher_info->pj_id }}" class="checkboxes" value="{{ $voucher_info->pj_id }}" /></td>
   <td>{{ $voucher_info->pj_id }}</td>
   <td>{{ $voucher_info->pj_creation_date }}</td>
   <td>{{ $voucher_info->pj_code }}</td> 
   <td>{{ $voucher_info->CreditAccount->aa_account_label }}</td>
   <td>{{ number_format( $voucher_info->pj_payment_amount, 2 ) }}</td> 
   <td><b>{{ $currency_array[ $voucher_info->pj_currency_id ]['cc_currency_code'] }}</b></td>
    <td><a href="#" data-pj_id="{{ $voucher_info->pj_id }}" id="EDIT_PJ_{{ $voucher_info->pj_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
    <td><a href="#" data-pj_id="{{ $voucher_info->pj_id }}"  id="DELETE_PJ_{{ $voucher_info->pj_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach