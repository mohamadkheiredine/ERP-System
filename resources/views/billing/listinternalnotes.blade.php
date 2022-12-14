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

@foreach($lst_internal_notes as $index => $in_info)
<tr  class="odd gradeX" data-in_id="{{ $in_info->in_id }}">
	<td><input type="checkbox" name="ck_in_{{ $in_info->in_id }}" id="CK_IN_{{ $in_info->in_id }}" class="checkboxes" value="{{ $in_info->in_id }}" /></td>
   <td>{{ $in_info->in_id }}</td>
   <td>{{ $in_info->SenderAccount->aa_account_ref }} - {{ $in_info->SenderAccount->aa_account_label }}</td>
    <td>{{ $in_info->ReceivableAccount->aa_account_ref }} - {{ $in_info->ReceivableAccount->aa_account_label }}</td>  
    <td>{{ $in_info->in_transfert_label }}</td>  
    <td>{{ $in_info->in_credit_value }}   <b>{{ $in_info->Currency->cc_currency_code }}</b></td>  
    <td>{{ $in_info->in_debit_value }}   <b>{{ $in_info->Currency->cc_currency_code }}</b></td>  
    <td><a href="#" data-in_id="{{ $in_info->in_id }}" id="EDIT_IN_{{ $in_info->in_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-in_id="{{ $in_info->in_id }}"  id="DELETE_IN_{{ $in_info->in_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach