<?php
/***********************************************************
listrecurringinvoices
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
 page to display list recurring invoices
***********************************************************/


?>


@foreach($lst_recinvoices as $index => $recinvoice_info)
<tr  class="odd gradeX" data-ri_id="{{ $recinvoice_info->ri_id }}">
	<td><input type="checkbox" name="ck_ri_{{ $recinvoice_info->ri_id }}" id="CK_RI_{{ $recinvoice_info->ri_id }}" class="checkboxes" value="{{ $recinvoice_info->ri_id }}" /></td>
   <td>{{ $recinvoice_info->ri_id }}</td>
   <td>{{ $recinvoice_info->Customer->ic_customer_name }}</td>
    <td>{{ $recinvoice_info->Account->ca_account_name }}</td>
    <td>{{ $recinvoice_info->ri_recurring_title }}</td>
    <td>{{ $recinvoice_info->ri_next_invoice_date }}</td>
    <td>{{ $recinvoice_info->ri_end_date }}</td>
    <td>{{ $recinvoice_info->ri_status }}</td>
   <td>{{ ($recinvoice_info->CreatedUser != null) ? $recinvoice_info->CreatedUser->u_fullname : "N/A" }}</td>
   <td>{{ ($recinvoice_info->UpdatedUser != null) ? $recinvoice_info->UpdatedUser->u_fullname : "N/A" }}</td>
    <td><a href="#" data-ri_id="{{ $recinvoice_info->ri_id }}" id="EDIT_RECINVOICE_{{ $recinvoice_info->ri_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
    <td><a href="#" data-ri_id="{{ $recinvoice_info->ri_id }}"  id="DELETE_RECINVOICE_{{ $recinvoice_info->ri_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach