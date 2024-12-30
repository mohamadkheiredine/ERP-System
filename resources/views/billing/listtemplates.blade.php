<?php

/***********************************************************
listtemplates
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 16, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>


@foreach($lst_templates as $index => $template_info)
<tr  class="odd gradeX" data-it_id="{{ $template_info->it_id }}">
	<td><input type="checkbox" name="ck_it_{{ $template_info->it_id }}" id="CK_IT_{{ $template_info->it_id }}" class="checkboxes" value="{{ $template_info->it_id }}" /></td>
   <td>{{ $template_info->it_id }}</td>
   <td>{{ $template_info->it_template_code }}</td>
    <td>{{ $template_info->it_template_label }}</td>
    <td><span style=" display: inline-block;width: 180px;white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;">{{ strip_tags($template_info->it_invoice_note) }}</span></td> 
   <td>{{ ($template_info->it_customer_id != 0 ) ?  $invoice_info->Customers->ic_customer_name : "N/A" }}</td>
   <td>{{	number_format($template_info->it_total_price,2) }}&nbsp;&nbsp;<b>{{ $template_info->Currency->cc_currency_code }}</b></td>
   <td>{{ ($template_info->CreatedUser != null) ? $template_info->CreatedUser->u_fullname : "N/A" }}</td>
   <td>{{ ($template_info->UpdatedUser != null) ? $template_info->UpdatedUser->u_fullname : "N/A" }}</td>
    <td><a href="#" data-it_id="{{ $template_info->it_id }}" id="EDIT_TEMPLATE_{{ $template_info->it_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
    <td><a href="#" data-it_id="{{ $template_info->it_id }}"  id="DELETE_TEMPLATE_{{ $template_info->it_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach