<?php
/***********************************************************
listaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

 @foreach ( $lst_accounts as $key => $account_info )
      <tr class="odd gradeX" data-aa_id="{{ $account_info->aa_id }}">
    	<td><input type="checkbox" name="ck_aa_{{ $account_info->aa_id }}" id="CK_AA_{{ $account_info->aa_id }}" class="checkboxes" value="{{ $account_info->aa_id }}" /></td> 
		<td>{{ $account_info->aa_account }}</td>
		<td>{{ ( $account_info->aa_sub_account != null && $account_info->aa_sub_account != 0 && isset($chart_accounts_array[$account_info->aa_sub_account]) ) ? $chart_accounts_array[$account_info->aa_sub_account]['aa_account'] : "" }}</td>
		<td>{{ $account_info->aa_account_label }}</td>
		<td>{{ $account_info->aa_account_information }}</td>
		 <td style="width:2px;"><a  data-aa_id="{{ $account_info->aa_id }}"  href="#"  id="EDIT_ACCOUNT_{{ $account_info->aa_id }}" ><i class="fa-regular fa-pen-to-square"></i></a></td>
           <td style="width:2px;">
            <a  data-aa_id="{{ $account_info->aa_id }}"  href="#"  id="DELETE_ACCOUNT_{{ $account_info->aa_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
          </td>
	</tr>
 @endforeach
 