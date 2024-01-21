<?php
/***********************************************************
listbanking.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
@foreach($lst_bank_entries  as $index => $entry_info)
<tr  class="odd gradeX" data-be_id="{{ $entry_info->be_id }}">
	<td><input type="checkbox" name="ck_be_{{ $entry_info->be_id }}" id="CK_BE_{{ $entry_info->be_id }}" class="checkboxes" value="{{ $entry_info->be_id }}" /></td>
   <td>{{ $entry_info->be_id }}</td>
   <td>{{ $entry_info->bankaccounts->ba_bank_name }}</td>
   <td>{{ $entry_info->be_entry_label }}</td> 
   <td>{{ $entry_info->be_entry_amount }}&nbsp;&nbsp;<b>{{ $entry_info->currency->cc_currency_code }}</b></td> 
    <td><a href="#" data-be_id="{{ $entry_info->be_id }}" id="EDIT_ENTRY_{{ $entry_info->be_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-be_id="{{ $entry_info->be_id }}"  id="DELETE_ENTRY_{{ $entry_info->be_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach