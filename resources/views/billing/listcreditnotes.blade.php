<?php
/***********************************************************
listinvoices.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@foreach($lst_credit_notes as $index => $cn_info)
<tr  class="odd gradeX" data-cn_id="{{ $cn_info->cn_id }}">
	<td><input type="checkbox" name="ck_cn_{{ $cn_info->cn_id }}" id="CK_CN_{{ $cn_info->cn_id }}" class="checkboxes" value="{{ $cn_info->cn_id }}" /></td>
   <td>{{ $cn_info->cn_id }}</td>
   <td>{{ $cn_info->SenderAccount->aa_account_ref }} - {{ $cn_info->SenderAccount->aa_account_label }}</td>
    <td>{{ $cn_info->ReceivableAccount->aa_account_ref }} - {{ $cn_info->ReceivableAccount->aa_account_label }}</td>  
    <td>{{ $cn_info->cn_credit_label }}</td>  
    <td>{{ $cn_info->cn_credit_value }}   <b>{{ $cn_info->Currency->cc_currency_code }}</b></td>  
    <td><a href="#" data-cn_id="{{ $cn_info->cn_id }}" id="EDIT_CN_{{ $cn_info->cn_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-cn_id="{{ $cn_info->cn_id }}"  id="DELETE_CN_{{ $cn_info->cn_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach