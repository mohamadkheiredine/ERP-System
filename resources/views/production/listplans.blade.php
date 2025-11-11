<?php
 /***********************************************************
 listplans.blade.php
 Product :
 Version : 1.0
 Release : 1
 Date Created : Jan 15, 2020
 Developed By  : Mohamad Mantach   PHP Department itm Solutions
 All Rights Reserved ,   itm Solutions COPYRIGHT 2020

 Page Description :

 ***********************************************************/

?>
@foreach($lst_production_plan as $index => $pp_info)
<tr bgcolor="{{ $pp_info->Status->ps_status_color }}"  class="odd gradeX" data-pp_id="{{ $pp_info->pp_id }}">
	<td><input type="checkbox" name="ck_pp_{{ $pp_info->pp_id }}" id="CK_PP_{{ $pp_info->pp_id }}" class="checkboxes" value="{{ $pp_info->pp_id }}" /></td>
   <td>{{ $pp_info->pp_id }}</td>
   <td>{{ $pp_info->pp_plan_code }}</td>
   <td>{{ $pp_info->pp_plan_label }}</td>
   <td>{{ $pp_info->Status->ps_status_title }}</td>
   <td>{{ $pp_info->Users->u_fullname }}</td>
  <td style="width:2px;">  <a href="#"  data-pp_id="{{ $pp_info->pp_id }}" id="EDIT_PLAN_{{ $pp_info->pp_id }}" ><i class="fa fa-pencil" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;"> <a href="#"  data-pp_id="{{ $pp_info->pp_id }}"  id="DELETE_PLAN_{{ $pp_info->pp_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach
