<?php
/***********************************************************
invoiceproducts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table border="0" class="tablesection" style="background-color:white;width:100%;">
	<thead>
		<tr>
			<th style="background-color: #c0c0c0">Item</th>
			<th align="center" style="text-align: center !important;background-color: #c0c0c0">Cost</th>
			<th style="width:100px;background-color: #c0c0c0" nowrap>Quantity</th>
			<th style="width:100px;background-color: #c0c0c0">Price</th>
		</tr>
	</thead>
	<tbody>
		@foreach( $items_array as $index => $item_info )
		<tr>
			<td style="height:45px;text-align: left !important;border-top:solid 1px black" align="center">{{ $item_info['label'] }} {{ isset($item_info['serialnumber']) ? $item_info['serialnumber'] : "" }}</td>
			<td style="height:45px;border-top:solid 1px black" align="center">{{ $item_info['cost'] }}&nbsp;&nbsp;{{ $item_info['currency'] }}</td>
			<td style="height:45px;border-top:solid 1px black" align="center">{{ $item_info['quantity'] }}</td>
			<td style="height:45px;border-top:solid 1px black" align="center">{{ $item_info['price'] }}&nbsp;&nbsp;{{ $item_info['currency'] }}</td>
		</tr>
		@endforeach
		<tr style="background-color: white;color:black;">
			<th colspan="2" style="border-width: 0px;"></th>
    		<th align="left" style="height:35px;text-align: left;background-color: #c0c0c0">Cost</th>
    		<th style="height:35px;background-color: #c0c0c0">{{ $total_cost }}&nbsp;&nbsp;{{ $currency }}</th>
    	</tr>
    	<tr style="background-color: white;color:black;display: none">
    		<th colspan="2" style="border-width: 0px;"></th>
    		<th align="left"  style="height:35px;text-align: left;background-color: #c0c0c0" >Tax</th>
    		<th style="height:35px;background-color: #c0c0c0">{{ $total_tax }}&nbsp;%</th>
    	</tr>
    	<tr style="background-color: white;color:black;">
    		<th colspan="2" style="border-width: 0px;"></th>
    		<th  align="left"  style="height:35px;text-align: left;background-color: #c0c0c0" >Discount</th>
    		<th style="height:35px;background-color: #c0c0c0">{{ $total_discount }}&nbsp;%</th>
    	</tr>
    	<tr style="background-color: white;color:black;">
    		<th colspan="2" style="border-width: 0px"></th>
    		<th align="left"  style="height:35px;text-align: left;background-color: #c0c0c0" > Total Cost </th>
    		<th style="height:35px;background-color: #c0c0c0">{{ $total_price }}&nbsp;&nbsp;{{ $currency }}</th>
    	</tr>
	</tbody>

</table>
