<?php
/***********************************************************
lstcategories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($lst_costcemter_categories  as $index => $category_info)
    <tr  class="odd gradeX"  data-cca_id="{{ $category_info->cca_id }}">
    	<td><input type="checkbox" name="ck_cca_{{ $category_info->cca_id }}" id="CK_CCA_{{ $category_info->cca_id }}" class="checkboxes" value="{{ $category_info->cca_id }}" /></td>
       <td>{{ $category_info->cca_id }}</td>
       <td>{{ $category_info->cca_category_name }}</td> 
       <td>{{ ( $category_info->fk_cca_id == 0 ) ? '-' : $category_info->Category->cca_category_name }}</td> 
        <td><a href="#" data-cca_id="{{ $category_info->cca_id }}" id="EDIT_CATEGORY_{{ $category_info->cca_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-cca_id="{{ $category_info->cca_id }}"  id="DELETE_CATEGORY_{{ $category_info->cca_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach