<?php
/***********************************************************
 * listcycles.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 11/22/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($lst_farm_cycles as $index => $cycle_info)
    <tr   class="odd gradeX" data-fc_id="{{ $cycle_info->fc_id }}">
        <td><input type="checkbox" name="ck_fc_{{ $cycle_info->fc_id }}" id="CK_FC_{{ $cycle_info->fc_id }}" class="checkboxes" value="{{ $cycle_info->fc_id }}" /></td>
        <td>{{ $cycle_info->fc_code }}</td>
        <td>{{ $cycle_info->fc_farm_name }}</td>
        <td></td>
        <td>{{ $cycle_info->fc_start_date }}</td>
        <td>{{ $cycle_info->fc_end_date }}</td>
        <td style="width:2px;">  <a href="#"  data-fc_id="{{ $cycle_info->fc_id }}" id="EDIT_CYCLE_{{ $cycle_info->fc_id }}" ><i class="fa fa-pencil" aria-hidden="true" height="16" ></i></a> </td>
        <td style="width:2px;"> <a href="#"  data-fc_id="{{ $cycle_info->fc_id }}"  id="DELETE_CYCLE_{{ $cycle_info->fc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
    </tr>
@endforeach

