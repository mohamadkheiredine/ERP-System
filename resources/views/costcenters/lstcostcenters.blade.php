<?php
/***********************************************************
lstcostcenters.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($lst_costcenters_info  as $index => $ccenter_info)
    <tr  class="odd gradeX"  data-ac_id="{{ $ccenter_info->ac_id }}">
    	<td><input type="checkbox" name="ck_ac_{{ $ccenter_info->ac_id }}" id="CK_AC_{{ $ccenter_info->ac_id }}" class="checkboxes" value="{{ $ccenter_info->ac_id }}" /></td>
       <td>{{ $ccenter_info->ac_id }}</td>
       <td>{{ $ccenter_info->Category->cca_category_name }}</td> 
       <td>{{ $ccenter_info->Manager->u_fullname }}</td> 
       <td>{{ $ccenter_info->Type->at_type_name }}</td> 
       <td>{{ $ccenter_info->ac_cost_center_label }}</td> 
        <td><a href="#" data-ac_id="{{ $ccenter_info->ac_id }}" id="EDIT_COSTCENTER_{{ $ccenter_info->ac_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-ac_id="{{ $ccenter_info->ac_id }}"  id="DELETE_COSTCENTER_{{ $ccenter_info->ac_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach