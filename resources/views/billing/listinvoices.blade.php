<?php
/***********************************************************
listinvoices.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($lst_invoices as $index => $invoice_info)
<tr  class="odd gradeX" data-bi_id="{{ $invoice_info->bi_id }}">
	<td><input type="checkbox" name="ck_bi_{{ $invoice_info->bi_id }}" id="CK_BI_{{ $invoice_info->bi_id }}" class="checkboxes" value="{{ $invoice_info->bi_id }}" /></td>
   <td>{{ $invoice_info->bi_id }}</td>
   <td>{{ $invoice_info->bi_invoice_ref }}</td>
    <td>{{ $invoice_info->bi_invoice_date }}</td>
    <td><span style=" display: inline-block;width: 180px;white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;">{{ strip_tags($invoice_info->bi_invoice_note) }}</span></td>
    @if(Config::get('appconfig.crm_telemarketing') == 0)
    <td>{{ ($invoice_info->fk_customer_id != 0 ) ?  $customers_array[$invoice_info->fk_customer_id]['ic_customer_name'] : "N/A" }}</td>
    @else
    <td>{{ ($invoice_info->bi_client_id != 0 ) ?  $invoice_info->Client->ca_account_name : "-" }}</td>
    @endif
   <td>{{	number_format($invoice_info->bi_total_price,2) }}&nbsp;&nbsp;<b>{{ $invoice_info->Currency ? $invoice_info->Currency->cc_currency_code : "" }}</b></td>
   <td>{{ ($invoice_info->CreatedUser != null) ? $invoice_info->CreatedUser->u_fullname : "N/A" }}</td>
   <td>{{ ($invoice_info->UpdatedUser != null) ? $invoice_info->UpdatedUser->u_fullname : "N/A" }}</td>
    <td style="text-align: center"><a target="_blank" href="{{ url('billing/invoices/downloadinvoice/' . $invoice_info->bi_id ) }}"><i class='fa fa-download'></i></a></td>
    <td><a href="#" data-bi_id="{{ $invoice_info->bi_id }}" id="EDIT_INVOICE_{{ $invoice_info->bi_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
    <td><a href="#" data-bi_id="{{ $invoice_info->bi_id }}"  id="DELETE_INVOICE_{{ $invoice_info->bi_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach
