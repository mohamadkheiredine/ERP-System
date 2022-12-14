<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($product_categories as $index => $product_cat_info)
<tr class="odd gradeX" data-pc_id="{{ $product_cat_info['pc_id'] }}">
	<td><input type="checkbox" name="ck_pc_{{ $product_cat_info['pc_id'] }}" id="CK_PC_{{ $product_cat_info['pc_id'] }}" class="checkboxes" value="{{ $product_cat_info['pc_id'] }}" /></td>
   <td>{{ $product_cat_info['pc_id'] }}</td>
   <td>{{ $product_cat_info['pc_category'] }}</td>
   <td>{{ ( $product_cat_info['fk_pc_id'] == NULL || !isset($product_categories_array[ $product_cat_info['fk_pc_id'] ]) ) ? "N/A" : $product_categories_array[ $product_cat_info['fk_pc_id'] ] }}</td>
   <td align="center"><a href="{{ url('inventory/categories/listitems/' . $product_cat_info['pc_id']) }}" id="DISPLAY_LIST_{{ $product_cat_info['pc_id'] }}"><i class="fa fa-bars" height="16"></i></a></td>
    <td><a href="#"  id="EDIT_PRODUCT_CATEGORY_{{ $product_cat_info['pc_id'] }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" id="DELETE_PRODUCT_CATEGORY_{{ $product_cat_info['pc_id'] }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach