<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

 @foreach( $lst_suppliers as $key => $sup_info )
    <tr  class="odd gradeX" data-ss_id="{{ $sup_info->ss_id }}">
    	<td><input type="checkbox" name="ck_ss_{{ $sup_info->ss_id }}" id="CK_SS_{{ $sup_info->ss_id }}" class="checkboxes" value="{{ $sup_info->ss_id }}" /></td>
		<td>{{ $sup_info->ss_supplier_name }}</td> 
		<td>{{  $sup_info->ss_supplier_phone  }}</td>
		 <td style="width:2px;"><a  data-ss_id="{{ $sup_info->ss_id }}"  href="#"  id="EDIT_SUPPLIER_{{ $sup_info->ss_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
           <td style="width:2px;"><a  data-ss_id="{{ $sup_info->ss_id }}"  href="#"  id="DELETE_SUPPLIER_{{ $sup_info->ss_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
	</tr>
 @endforeach