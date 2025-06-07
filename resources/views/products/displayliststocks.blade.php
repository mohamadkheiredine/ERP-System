<?php
/***********************************************************
displayliststocks.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($productStocks as $index => $ps_info)
<tr  class="odd gradeX" data-is_id="{{ $ps_info->is_id }}">
	<td><input type="checkbox" name="ck_is_{{ $ps_info->is_id }}" id="CK_IS_{{ $ps_info->is_id }}" class="checkboxes" value="{{ $ps_info->is_id }}" /></td>
   <td>{{ $ps_info->is_id }}</td>
   <td>{{ $ps_info->is_stock_label }}</td>
   <td>{{ $ps_info->is_created_by }}</td>
   <td>{{ $warehouses_array[$ps_info->fk_warehouse_id]['w_warehouse_name'] }}</td>
   <td>{{ $ps_info->is_quanity }}</td>
   <td>{{ $ps_info->is_price_stock }}</td>
    <td style="width:2px;">  <a href="#"  data-is_id="{{ $ps_info->is_id }}" id="EDIT_STOCK_{{ $ps_info->is_id }}" ><i class="fa-regular fa-pen-to-square"></i></i></a> </td>
    <td style="width:2px;"> <a href="#"  data-is_id="{{ $ps_info->is_id }}"  id="DELETE_STOCK_{{ $ps_info->is_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach
