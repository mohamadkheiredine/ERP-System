<?php
/***********************************************************
listvehicules.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 7, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($lst_vehicules as $index => $veh_info)
<tr  class="odd gradeX" data-lv_id="{{ $veh_info->lv_id }}">
	<td><input type="checkbox" name="ck_lv_{{ $veh_info->lv_id }}" id="CK_LV_{{ $veh_info->lv_id }}" class="checkboxes" value="{{ $veh_info->lv_id }}" /></td>
   <td>{{ $veh_info->lv_id }}</td>
   <td>{{ $veh_info->lv_vehicule_name }}</td>
   <td>{{ $veh_info->lv_vehicule_number }}</td>
   <td>{{ $veh_info->lv_model_year }}</td>
    <td><a href="#"  id="EDIT_VEHICULE_{{ $veh_info->lv_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" id="DELETE_VEHICULE_{{ $veh_info->lv_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach