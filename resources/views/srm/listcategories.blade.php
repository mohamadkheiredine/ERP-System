<?php
/***********************************************************
listcategories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($lst_srm_categories  as $index => $sc_info)
<tr  class="odd gradeX" data-sc_id="{{ $sc_info->sc_id }}">
	<td><input type="checkbox" name="ck_sc_{{ $sc_info->sc_id }}" id="CK_SC_{{ $sc_info->sc_id }}" class="checkboxes" value="{{ $sc_info->sc_id }}" /></td>
   <td>{{ $sc_info->sc_id }}</td>
   <td>{{ ( $sc_info->fk_category_id != null ) ? $supplier_categories_array[$sc_info->fk_category_id]['sc_category_title'] : "N/A" }}</td>
   <td>{{ $sc_info->sc_category_ref }}</td>
   <td>{{ $sc_info->sc_category_title }}</td> 
    <td><a href="#" data-sc_id="{{ $sc_info->sc_id }}" id="EDIT_CATEGORY_{{ $sc_info->sc_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-sc_id="{{ $sc_info->sc_id }}"  id="DELETE_CATEGORY_{{ $sc_info->sc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach