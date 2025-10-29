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


@foreach($order_products_array as $index => $op_info)
<tr class="odd gradeX" data-p_id="{{ $op_info['product_id'] }}">
  <td><input type="checkbox" name="ck_p_{{ $op_info['product_id'] }}" id="CK_P_{{ $op_info['product_id'] }}" class="checkboxes" value="{{ $op_info['product_id'] }}" /></td>
  <td>{{ $op_info['product_id'] }}</td>
  <td>{{ $op_info['barcode'] }}</td>
  <td><img style="width:64px" src="{{ $op_info['image_url'] }}" /></td>
  <td>{{ $op_info['product_name'] }}</td>
  <td>{{ $op_info['price_item'] }}&nbsp;&nbsp;<b>{{ $op_info['stock_currency_code'] }}</b></td>
  <td>{{ $op_info['quantity'] }}</td>
  <td>{{ $op_info['price_stock'] }}&nbsp;&nbsp;<b>{{ $op_info['stock_currency_code'] }}</b></td>
  <td>
    <button type="button" class="btn btn-danger btn-delete-product" data-stock_id="{{ $op_info['stock_id'] }}" id="BTN_DELETE_PRODUCT_{{ $op_info['product_id'] }}">
      <i class="bi bi-trash flex align-center items-center"></i>
    </button>
  </td>
</tr>
@endforeach
