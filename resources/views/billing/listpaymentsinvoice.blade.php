<?php
/***********************************************************
listpaymentsinvoice.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List of Payments in selected invoice
***********************************************************/

?>

<table id="LstPayments" class="table m-table m-table--head-bg-success">
	<thead>
		<tr> 
			<th style="width:40%">Section Label </th>
			<th style="width:30%">Section Payment Type </th>
			<th style="width:15%">Section Percentage</th>
			<th style="width:10%">Total</th>
			<th style="width:5%;white-space: nowrap;">Actions</th>
		</tr>
	</thead>
	<tbody>
		@foreach($lst_payments_invoice as $pi_index => $pi_info)
		<tr class="Invoices" id="ROW_{{ $pi_index }}"> 
			<td><input <?php echo ($invoice_info->bi_invoice_status == 1 ? "readonly='readonly'" : ""); ?> type="text" name="ip_payment_label[]"  style="width:100%" class="form-control" value="{{ $pi_info->ip_payment_label }}" /></td>
			<td>
				<select class="bs-select form-control" style="width:100%" name="ip_payment_type[]">
        			<option value="">-- Select Payment Type --</option>
                    @foreach($lst_payments as $index => $paytype_info)
                      <option {{ $pi_info->ip_payment_type == $paytype_info->pt_id ? "selected" : ""  }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                    @endforeach
                </select>
			</td>
			<td><input <?php echo ($invoice_info->bi_invoice_status == 1 ? "readonly='readonly'" : ""); ?>  type="number" step="0.1" min="0" max="100" name="ip_payment_percentage[]"  style="width:100%" class="form-control" value="{{ $pi_info->ip_payment_percentage }}" /></td>
			<td>{{ $invoice_info->bi_total_cost * ($pi_info->ip_payment_percentage / 100) }}&nbsp;&nbsp;<b>{{  $currency }}</b></td>
			<td>&nbsp;&nbsp;<?php if($invoice_info->bi_invoice_status == 1){ ?><a href="#" class="DeleteRow" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a><?php } ?>&nbsp;&nbsp;</td>
		</tr>
		@endforeach
			<tr class="EmptyRow" style="display:none"> 
			<td><input type="text" name="ip_payment_label[]"  style="width:100%" class="form-control" value="" /></td>
			<td>
				<select class="bs-select form-control" id="IP_PAYMENT_TYPE" name="ip_payment_type[]">
        			<option value="">-- Select Payment Type --</option>
                    @foreach($lst_payments as $index => $paytype_info)
                      <option value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                    @endforeach
                </select>
			</td>
			<td><input type="number" step="0.1" min="0" max="100" name="ip_payment_percentage[]"  style="width:100%" class="form-control" value="" /></td> 
			<td class="PaymentValue"></td>
			<td>&nbsp;&nbsp;<a href="#" class="DeleteRow" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>&nbsp;&nbsp;</td>
		</tr>
	</tbody>
</table>