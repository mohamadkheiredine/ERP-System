<?php
/***********************************************************
lstbills
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 22, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>


@foreach($lst_bills_info as $index => $bill_info)
<tr  class="odd gradeX" data-ip_id="{{ $bill_info->ip_id }}">
	<td><input type="checkbox" name="ip_checkbox_{{ $bill_info->ip_id }}" id="IP_CHECKBOX_{}{ $bill_info->ip_id }}" class="checkboxes" value="{{ $bill_info->ip_id }}" /></td>
   <td>{{ $bill_info->ip_id }}</td>
   <td>{{ $bill_info->ip_payment_doc }}</td>
   <td>{{ $bill_info->ip_billing_date }}</td>
   <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_code : "-" }}</td>
   <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_name : "-" }}</td>
   <td>{{ $bill_info->ip_payment_amount }}</td>
   <td>{{ $bill_info->Currency ? $bill_info->Currency->cc_currency_code : "-" }}</td>
    <td><a href="#" data-ip_id="{{ $bill_info->ip_id }}" id="EDIT_IP_{ $voucher_info->pv_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
    <td><a href="#" data-ip_id="{{ $bill_info->ip_id }}"  id="DELETE_IP_{ $voucher_info->pv_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach