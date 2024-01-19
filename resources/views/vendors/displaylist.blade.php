<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


	@foreach($lst_vendors_obj as $index => $vendor_info)
                    <tr  class="odd gradeX" data-iv_id="{{ $vendor_info->iv_id }}">
                    	<td><input type="checkbox" name="ck_iv_{{ $vendor_info->iv_id }}" id="CK_IV_{{ $vendor_info->iv_id }}" class="checkboxes" value="{{ $vendor_info->iv_id }}" /></td>
                       <td>{{ ($vendor_info->iv_vendor_account_id > 0) ? $vendor_info->accounts->aa_account_ref . " - " . $vendor_info->accounts->aa_account_label : "N/A" }}</td>
                       <td>{{ $vendor_info->iv_vendor_code }}</td> 
                       <td>{{ $vendor_info->iv_vendor_name }}</td>
                       <td>{{ $vendor_info->iv_vendor_phone }}</td>
                        <td><a href="#" data-iv_id="{{ $vendor_info->iv_id }}" id="EDIT_VENDOR_{{ $vendor_info->iv_id }}" ><i class="fas fa-edit" height="16"></i></a></td> 
                        <td><a href="#" data-iv_id="{{ $vendor_info->iv_id }}"   id="DELETE_VENDOR_{{ $vendor_info->iv_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td> 
                    </tr>
                    @endforeach