<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
 
?>


@foreach($lst_products as $index => $pp_info)
<tr class="odd gradeX" data-p_id="{{ $pp_info->p_id }}">
	<td><input type="checkbox" name="ck_pp_{{ $pp_info->p_id }}" id="CK_PP_{{ $pp_info->p_id }}" class="checkboxes" value="{{ $pp_info->p_id }}" /></td>
   <td>{{ $pp_info->p_id }}</td>
   <td>{{ $pp_info->p_product_ref }}</td>
   <td>{{ $pp_info->p_product_name }}</td>
   <td>{{ $pp_info->p_product_selling_price }}&nbsp;&nbsp;<b>{{  $pp_info->p_product_currency == null  ? session('currency_symbol') : $currency_array[ $pp_info->p_product_currency ]['cc_currency_code'] }}</b></td>
  <td style="width:2px;">  <a href="#"  data-p_id="{{ $pp_info->p_id }}" id="EDIT_PRODUCT_{{ $pp_info->p_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-p_id="{{ $pp_info->p_id }}"  id="DELETE_PRODUCT_{{ $pp_info->p_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach