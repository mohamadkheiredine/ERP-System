<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 9, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display List of orders
***********************************************************/



?>

@foreach($list_orders  as $index => $order_info)
<tr  class="odd gradeX" data-so_id="{{ $order_info->so_id }}">
	<td><input type="checkbox" name="ck_so_{{ $order_info->so_id }}" id="CK_SO_{{ $order_info->so_id }}" class="checkboxes" value="{{ $order_info->so_id }}" /></td>
   <td>{{ $order_info->so_id }}</td>
   <td>{{ $order_info->so_order_code }}</td> 
   <td>{{ $order_info->so_order_label }}</td> 
   <td>{{  number_format($order_info->so_total_cost , 2) }}&nbsp;&nbsp;<b>{{ $order_info->Currency ? $order_info->Currency->cc_currency_code : "" }}</b></td> 
    <td><a href="#" data-so_id="{{ $order_info->so_id }}" id="EDIT_ORDER_{{ $order_info->so_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-so_id="{{ $order_info->so_id }}"  id="DELETE_ORDER_{{ $order_info->so_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach