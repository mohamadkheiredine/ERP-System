<?php
/***********************************************************
displaylistproducts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 10, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display list of Products 
***********************************************************/


?>

<table class="m-datatable" id="html_table" width="50%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="barcode"> Barcode </th>
				<th title="image"> Image </th>
				<th title="Product Name">Product Name</th>
				<th title="Products Item">Item Price</th>
				<th title="Quanity">Quanity</th>
				<th title="Products Pice">Products Pice</th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($order_products_array  as $index => $op_info)
                <tr  class="odd gradeX" data-p_id="{{ $op_info['product_id'] }}">
                	<td><input type="checkbox" name="ck_p_{{ $op_info['product_id'] }}" id="CK_P_{{ $op_info['product_id'] }}" class="checkboxes" value="{{ $op_info['product_id'] }}" /></td>
                   <td>{{ $op_info['product_id'] }}</td>
                   <td>{{ $op_info['barcode'] }}</td>
                   <td><img style="width:64px" src="{{ $op_info['image_url'] }}" /></td>
                   <td>{{ $op_info['product_name'] }}</td> 
                   <td>{{ $op_info['price_item'] }}&nbsp;&nbsp;<b>{{ $op_info['stock_currency_code'] }}</b></td> 
                   <td>{{ $op_info['quantity'] }}</td>
                   <td>{{ $op_info['price_stock'] }}&nbsp;&nbsp;<b>{{ $op_info['stock_currency_code'] }}</td>
                </tr>
                @endforeach
		</tbody>
</table>