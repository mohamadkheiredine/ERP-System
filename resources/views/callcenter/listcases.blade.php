<?php
/***********************************************************
@file
Product : listcases
Version : 1.0
Release : 1
Date Created : Sep 2, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 

Page Description :
{Enter page description Here}
 * 
 * 
***********************************************************/


?>

@foreach($lst_cases_info as $index => $case_info)
<tr  class="odd gradeX" data-cc_id="{{ $case_info->cc_id }}" bgcolor="{{ $case_info->Status->cc_status_color }}">
	<td><input type="checkbox" name="ck_cc_{{ $case_info->cc_id }}" id="CK_CC_{{ $case_info->cc_id }}" class="checkboxes" value="{{ $case_info->cc_id }}" /></td>
   <td>{{ $case_info->cc_id }}</td>
   <td>{{ $case_info->cc_case_code }}</td>
   <td>{{ $case_info->Status ? $case_info->Status->cc_status_title : "N/A" }}</td>
   <td>{{ $case_info->Client->ca_account_code }}</td>
   <td>{{ $case_info->cc_contract_code }}</td>
   <td>{{ $case_info->cc_case_date }}</td>
   <td>{{ $case_info->cc_case_time }}</td>
   <td>{{ $case_info->Telemarketing ? $case_info->Telemarketing->u_fullname : "-" }}</td>
    <td><a href="#" data-cc_id="{{ $case_info->cc_id }}" id="EDIT_CASE_{{ $case_info->cc_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td> 
    <td><a href="#" data-cc_id="{{ $case_info->cc_id }}"  id="DELETE_CASE_{{ $case_info->cc_id }}" ><i class="fa-solid fa-trash"></i></a></td> 
</tr>
@endforeach
