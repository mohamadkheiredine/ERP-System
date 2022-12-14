<?php
/***********************************************************
listtypes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


	@foreach($lst_project_types  as $index => $pt_info)
                <tr  class="odd gradeX" data-pt_id="{{ $pt_info->pt_id }}">
                	<td><input type="checkbox" name="ck_pt_{{ $pt_info->pt_id }}" id="CK_PT_{{ $pt_info->pt_id }}" class="checkboxes" value="{{ $pt_info->pt_id }}" /></td>
                   <td>{{ $pt_info->pt_id }}</td>
                   <td>{{ $pt_info->pt_type_name }}</td>  
                    <td><a href="#" data-pt_id="{{ $pt_info->pt_id }}" id="EDIT_TYPE_{{ $pt_info->pt_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-pt_id="{{ $pt_info->pt_id }}"  id="DELETE_TYPE_{{ $pt_info->pt_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach