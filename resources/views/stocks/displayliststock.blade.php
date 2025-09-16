<?php
/***********************************************************
displayliststock.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@foreach($lst_stock as $index => $si_info)
<tr  class="odd gradeX" data-is_id="{{ $si_info->is_id }}">
	<td><input type="checkbox" name="ck_si_{{ $si_info->is_id }}" id="CK_SI_{{ $si_info->is_id }}" class="checkboxes" value="{{ $si_info->is_id }}" /></td>
   <td>{{ $si_info->is_id }}</td>
   <td>{{ $si_info->products->p_product_name }}</td>
   <td>{{ $si_info->warehouses->w_warehouse_name }}</td>
   <td>{{ number_format($si_info->is_price_stock,2) }}&nbsp;&nbsp;<b>{{  $currency_array[ $si_info->is_stock_currency ]['cc_currency_code'] }}</b></td>
   <td>{{ number_format($si_info->is_price_ite,2) }}&nbsp;&nbsp;<b>{{  $currency_array[ $si_info->is_stock_currency ]['cc_currency_code'] }}</b></td>
   <td>{{ $si_info->is_quanity }}</td>
  <td style="width:2px;">  <a href="#"  data-is_id="{{ $si_info->is_id }}" id="EDIT_STOCK_{{ $si_info->is_id }}" ><i class="fa-regular fa-pen-to-square"></i></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-is_id="{{ $si_info->is_id }}"  id="DELETE_STOCK_{{ $si_info->is_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach
