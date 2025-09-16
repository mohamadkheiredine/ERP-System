<?php
/***********************************************************
 * liststores.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/14/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>

@foreach($lst_stores  as $index => $store_info)
    <tr  class="odd gradeX" data-ps_id="{{ $store_info->ps_id }}">
        <td><input type="checkbox" name="ck_store_{{ $store_info->ps_id }}" id="CK_STORE_{{ $store_info->ps_id }}" class="checkboxes" value="{{ $store_info->ps_id }}" /></td>
        <td>{{ $store_info->ps_id }}</td>
        <td>{{ $store_info->ps_store_name }}</td>
        <td>{{ $store_info->Manager->u_fullname }}</td>
        <td><a href="#" data-ps_id="{{ $store_info->ps_id }}" id="EDIT_STORE_{{ $store_info->ps_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-ps_id="{{ $store_info->ps_id }}"  id="DELETE_STORE_{{ $store_info->ps_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach

