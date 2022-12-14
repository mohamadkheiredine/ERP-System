<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 9, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/

?>

@foreach($receipts as $index => $receipt_info)
<tr class="odd gradeX" data-br_id="{{ $receipt_info->br_id }}">
	<td><input type="checkbox" name="ck_br_{{ $receipt_info->br_id }}" id="CK_BR_{{ $receipt_info->br_id }}" class="checkboxes" value="{{ $receipt_info->br_id }}" /></td>
   	<td style="width:2px;">{{ $receipt_info->br_id }}</td>
	<td>{{ isset($receipt_info->Invoice) ? $receipt_info->Invoice->bi_invoice_code : "N/A" }}</td>
	<td><b>{{ $receipt_info->br_receipt_number }}</b></td>
	<td>{{ $receipt_info->br_receipt_label }}</td>
	<td><span style=" display: inline-block;width: 180px;white-space: nowrap;overflow: hidden !important;text-overflow: ellipsis;">{{ strip_tags($receipt_info->br_receipt_note) }}</span></td>
	<td>{{ $receipt_info->br_receipt_date }}</td>
	<td>{{ $receipt_info->br_payment_value }}&nbsp;<b>{{ $receipt_info->Currency->cc_currency_code }}</b></td> 
    <td><b><a  id="DOWNLOAD_{{ $receipt_info->br_id }}" target="_blank" title="download" href="<?php echo ( $receipt_info->br_receipt_paid == 1 ? url('billing/invoices/downloadreceipt/' . $receipt_info->br_id ): "" ); ?>"><i class='fa fa-download'></i></a></b>&nbsp;&nbsp;<?php if($receipt_info->br_receipt_paid == 0){ ?><b><a data-br_id="{{ $receipt_info->br_id }}" id="PAY_{{ $receipt_info->br_id }}" href="#"><i class='fa fa-dollar' title='pay'></i></a></b><?php } ?></td>
    <td><a href="#"  id="EDIT_RECEIPT_{{ $receipt_info->br_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" id="DELETE_RECEIPT_{{ $receipt_info->br_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach