<?php
/***********************************************************
listinvoicereceipts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 15, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List of recepits for a selected invoice  
***********************************************************/

?>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Receipt Number</th>
			<th>Receipt Date</th>
			<th>Payment Label</th>
			<th>Payment Value</th>
			<th>Paid</th>
			<th> edit </th>
			<th> actions </th>
		</tr>
	</thead>
	<tbody>
		@foreach( $lst_invoice_receipts as $index => $receipt_info )
		<tr  class="odd gradeX" data-br_id="{{ $receipt_info->br_id }}">
			<td><input type="checkbox" name="ck_br_{{ $receipt_info->br_id }}" id="CK_BR_{{ $receipt_info->br_id }}" class="checkboxes" value="{{ $receipt_info->br_id }}" /></td>
			<td>{{ $receipt_info->br_receipt_number }}</td>
			<td>{{ $receipt_info->br_receipt_date }}</td>
			<td>{{ $receipt_info->br_receipt_label }}</td>
			<td>{{ $receipt_info->br_payment_value }}&nbsp;&nbsp;{{ $currencies_array[ $receipt_info->br_receipt_currency ]['cc_currency_code'] }}</td>
			<td><b>{!! $receipt_info->br_receipt_paid == 1 ? "<span class='m--font-success'>Yes</span>" : "<span class='m--font-danger'>No</span>" !!}</b></td>
			<td><a href="#" data-br_id="{{ $receipt_info->br_id }}" id="EDIT_IRECEIPT_{{ $receipt_info->br_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
			<td><b><a  id="DOWNLOAD_{{ $receipt_info->br_id }}" target="_blank" title="download" href="<?php echo ( $receipt_info->br_receipt_paid == 1 ? url('billing/invoices/downloadreceipt/' . $receipt_info->br_id ): "" ); ?>"><i class='fa fa-download'></i></a></b>&nbsp;&nbsp;<?php if($receipt_info->br_receipt_paid == 0){ ?><b><a data-br_id="{{ $receipt_info->br_id }}" id="PAY_{{ $receipt_info->br_id }}" href="#"><i class='fa fa-dollar' title='pay'></i></a></b><?php } ?></td>
		</tr>
		@endforeach
	</tbody>
</table>