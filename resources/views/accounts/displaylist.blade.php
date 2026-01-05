<?php
/***********************************************************
displaylist.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@foreach($lst_accounts  as $index => $account_info)
<tr  class="odd gradeX" data-ca_id="{{ $account_info->ca_id }}">
	<td><input type="checkbox" name="ck_ca_{{ $account_info->ca_id }}" id="CK_CA_{{ $account_info->ca_id }}" class="checkboxes" value="{{ $account_info->ca_id }}" /></td>
   <td>{{ $account_info->ca_account_code }}</td>
   <td>{{ $account_info->ca_account_name }}</td>
   <td>{{ $account_info->ca_account_mobile }}</td>
   <td>{{ $account_info->ca_account_email }}</td>
   <td>{{ $account_info->ca_billing_address }}</td>
    <td><a href="#" data-ca_id="{{  $account_info->ca_id }}" id="EDIT_ACCOUNT_{{  $account_info->ca_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" title="View Client File" data-ca_id="{{  $account_info->ca_id }}" id="VIEW_ACCOUNT_{{  $account_info->ca_id }}" ><i class="fas fa-eye"></i></a></td>
    <td><a href="#" data-ca_id="{{  $account_info->ca_id }}"  id="DELETE_ACCOUNT_{{  $account_info->ca_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach
