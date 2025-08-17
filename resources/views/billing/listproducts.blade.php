<?php
use SebastianBergmann\CodeCoverage\Report\PHP;

/***********************************************************
listproducts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display Table  list of all products
***********************************************************/

?>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>Item</th>
			<th>Cost</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		@foreach( $items_array as $index => $item_info )
		<tr>
			<td>#</td>
			<td>{{ $item_info['label'] }}</td>
			<td>{{ $item_info['cost'] }}&nbsp;&nbsp;{{ $item_info['currency'] }}</td>
			<td>{{ $item_info['quantity'] }}</td>
			<td>{{ $item_info['price'] }}&nbsp;&nbsp;{{ $item_info['currency'] }}</td>
    		<td><a href="#" data-item_id="{{ $item_info['id'] }}"  id="DELETE_ITEM_{{ $item_info['id'] }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
		</tr>
		@endforeach
	</tbody>
	<tr style="background-color: #5656ff;color:white;">
		<th colspan="5" align="center">Cost</th>
		<th>{{ Config::get('appconfig.crm_telemarketing') == 1 ? number_format($invoice_info->bi_total_cost) : number_format($total_cost) }}&nbsp;&nbsp;{{ $currency }}</th>
	</tr>
	<tr style="background-color: #7673ff;color:white;">
		<th colspan="5" align="center">Tax</th>
		<th>{{ $total_tax }}&nbsp;%</th>
	</tr>
	<tr style="background-color: #7673ff;color:white;">
		<th colspan="5" align="center">Discount</th>
		<th>{{ $total_discount }}&nbsp;%</th>
	</tr>
	<tr style="background-color: #1200ff;color:white;">
		<th colspan="5" align="center"> Total Cost </th>
		<th>{{ Config::get('appconfig.crm_telemarketing') == 1 ? number_format($invoice_info->bi_total_price) : number_format($total_price) }}&nbsp;&nbsp;{{ $currency }}</th>
	</tr>
</table>
