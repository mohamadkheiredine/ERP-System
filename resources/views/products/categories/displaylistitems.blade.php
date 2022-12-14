<?php
/***********************************************************
displaylistitems.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 8, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Display List Items
***********************************************************/

?>

@foreach($lst_products as $index => $product_info)
<tr class="odd gradeX" data-p_id="{{ $product_info->p_id }}">
	<td><input type="checkbox" name="ck_product_{{ $product_info->p_id }}" id="CK_PRODUCT_{{ $product_info->p_id }}" class="checkboxes" value="{{ $product_info->p_id }}" /></td>
   <td>{{ $product_info->p_id }}</td>
   <td>{{ $product_info->p_product_name }}</td>
   <td><span class="SellingPrice">{{ $product_info->p_product_selling_price }}</span>&nbsp;&nbsp;<b>{{ $product_info->Currency->cc_currency_code }}</b></td>
   <td>{{ $product_info->p_product_discount }}&nbsp;&nbsp;<b>%</b></td>
   <td>{{ $product_info->p_wholesale_price }}&nbsp;&nbsp;<b>{{ $product_info->Currency->cc_currency_code }}</b></td>
   <td>{{ $product_info->p_vendor_price }}&nbsp;&nbsp;<b>{{ $product_info->Currency->cc_currency_code }}</b></td>
   <td><a href="#"  id="EDIT_PRODUCT_{{ $product_info->p_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
</tr>
@endforeach