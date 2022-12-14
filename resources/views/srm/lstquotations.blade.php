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
<table class="table m-table m-table--head-bg-brand" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th> 
				<th title="Supplier"> Supplier </th>
				<th title="Date Submit"> Date Submit </th>
				<th title="Total Price"> Total Price </th>
				<th title="Currency"> Currency </th>
				<th title="Status"> Status </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
				<th style="width:2px;" nowrap title="#"> View </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_supplier_quotations  as $index => $quot_info)
                <tr  class="odd gradeX" data-sq_id="{{ $quot_info->sq_id }}">
                	<td><input type="checkbox" name="ck_sq_{{ $quot_info->sq_id }}" id="CK_SQ_{{ $quot_info->sq_id }}" class="checkboxes" value="{{ $quot_info->sq_id }}" /></td>
                   <td>{{ $quot_info->sq_id }}</td> 
                   <td>{{ $suppliers_array[ $quot_info->fk_supplier_id ]['ss_supplier_name'] }}</td>
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
		</tbody>
</table>