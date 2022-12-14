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

@foreach($lst_debit_notes as $index => $dn_info)
<tr  class="odd gradeX" data-dn_id="{{ $dn_info->dn_id }}">
	<td><input type="checkbox" name="ck_dn_{{ $dn_info->dn_id }}" id="CK_DN_{{ $dn_info->dn_id }}" class="checkboxes" value="{{ $dn_info->dn_id }}" /></td>
   <td>{{ $dn_info->dn_id }}</td>
   <td>{{ $dn_info->SenderAccount->aa_account_ref }} - {{ $dn_info->SenderAccount->aa_account_label }}</td>
    <td>{{ $dn_info->ReceivableAccount->aa_account_ref }} - {{ $dn_info->ReceivableAccount->aa_account_label }}</td>  
    <td>{{ $dn_info->dn_debit_label }}</td>   
    <td>{{ $dn_info->dn_debit_value }}   <b>{{ $dn_info->Currency->cc_currency_code }}</b></td>  
    <td><a href="#" data-dn_id="{{ $dn_info->dn_id }}" id="EDIT_DN_{{ $dn_info->dn_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
    <td><a href="#" data-dn_id="{{ $dn_info->dn_id }}"  id="DELETE_DN_{{ $dn_info->dn_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach