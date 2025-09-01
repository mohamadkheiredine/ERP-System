<?php
/***********************************************************
lstquotations.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 31, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
@foreach($lst_supplier_quotations  as $index => $quot_info)
<tr  class="odd gradeX" data-sq_id="{{ $quot_info->sq_id }}">
	<td><input type="checkbox" name="ck_sq_{{ $quot_info->sq_id }}" id="CK_SQ_{{ $quot_info->sq_id }}" class="checkboxes" value="{{ $quot_info->sq_id }}" /></td>
   <td>{{ $quot_info->sq_id }}</td>
   <td>{{ $quot_info->sq_invoice_number }}</td>
   <td>{{ $quot_info->sq_container_number }}</td>
   <td>{{ $quot_info->Supplier ? $quot_info->Supplier->ss_supplier_name : "-" }}</td>
   <td>{{ $quot_info->sq_date_submit }}</td>
   <td>{{ $quot_info->sq_total_price }}</td>
   <td>{{ $currencies_array[ $quot_info->sq_currency_id ]['cc_currency_code'] }}</td>
   <td>{!! ( $quot_info->sq_quotation_approve == 0 ) ? "<span class='m--font-primary'>Pending</span>" : ( $quot_info->sq_quotation_approve == 1 ? "<span class='m--font-success'>Approved</span>" : "<span class='m--font-danger'>Denied</span>" )  !!}</td>
    <td>
    	<a href="#" data-sq_id="{{ $quot_info->sq_id }}" id="EDIT_QUOTATION_{{ $quot_info->sq_id }}" ><i class="fas fa-edit" height="16"></i></a>
   </td>
    <td>
    	@if( $quot_info->sq_quotation_approve == 0 )
    	<a href="#" data-sq_id="{{ $quot_info->sq_id }}"  id="DELETE_QUOTATION_{{ $quot_info->sq_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
    	@endif
   	</td>
    <td>
    	<!-- <a href="#" data-sq_id="{{ $quot_info->sq_id }}"  id="VIEW_QUOTATION_{{ $quot_info->sq_id }}" ><i class="fa  fa-info-circle" aria-hidden="true" height="16" ></i></a> -->
   	</td>
</tr>
@endforeach
