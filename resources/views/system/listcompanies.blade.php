<?php
/***********************************************************
listcompanies.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($lst_companies as $index => $cmp_info)
<tr  class="odd gradeX" data-cd_id="{{ $cmp_info->cd_id }}">
	<td><input type="checkbox" name="ck_cmp_{{ $cmp_info->cd_id }}" id="CK_CMP_{{ $cmp_info->cd_id }}" class="checkboxes" value="{{ $cmp_info->cd_id }}" /></td>
   <td>{{ $cmp_info->cd_id }}</td>
   <td>{{ $cmp_info->cd_company_name }}</td>
   <td>{{ $cmp_info->cd_company_owner }}</td>
  <td style="width:2px;">  <a href="#"  data-cd_id="{{ $cmp_info->cd_id }}" id="EDIT_COMPANY_{{ $cmp_info->cd_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a> </td>
  <td style="width:2px;">
  	@if($cmp_info->cd_primary_company == 0) 
  	<a href="#"  data-cd_id="{{ $cmp_info->cd_id }}"  id="DELETE_COMPANY_{{ $cmp_info->cd_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
  	@endif
  </td>
</tr>
@endforeach