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


@foreach($lst_order_categories  as $index => $category_info)
<tr  class="odd gradeX" data-category_id="{{ $category_info->fk_category_id }}">
	<td><input type="checkbox" name="ck_cat_{{ $category_info->fk_category_id }}" id="CK_CAT_{{ $category_info->fk_category_id }}" class="checkboxes" value="{{ $category_info->fk_category_id }}" /></td>
   <td>{{ $category_info->fk_category_id }}</td>
   <td>{{ $category_info->Category->pc_category }}</td>
   <td>{{ $category_info->so_package_weight }}</td>
   <td>{{ $category_info->so_package_price }}</td>
</tr>
@endforeach