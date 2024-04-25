<?php
/***********************************************************
listpacking.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Manage packing Prices Saved in database
***********************************************************/


?>



@foreach($lst_category_packing as $index => $packing_info)
<tr  class="odd gradeX"  data-cp_id="{{ $packing_info->cp_id }}">
  <td><input type="checkbox" name="ck_cp_{{ $packing_info->cp_id }}" id="CK_CP_{{ $packing_info->cp_id }}" class="checkboxes" value="{{ $packing_info->cp_id }}" /></td>
  <td>{{ $packing_info->cp_id }}</td>
  <td>{{ $packing_info->Category->pc_category }}</td>
  <td>{{ $packing_info->cp_weight_from  }}&nbsp;<b></b></td>
  <td>{{ $packing_info->cp_weight_to  }}</td>
  <td>{{ $packing_info->cp_price_range  }}</td>
  <td style="width:2px;"><a href="#"  data-cp_id="{{ $packing_info->cp_id }}" id="EDIT_PACKING_{{ $packing_info->cp_id }}" ><i class="fa-solid fa-pen-to-square"></i></a> </td>
  <td style="width:2px;"><a href="#"  data-cp_id="{{ $packing_info->cp_id }}" id="DELETE_PACKING_{{ $packing_info->cp_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
</tr>
@endforeach