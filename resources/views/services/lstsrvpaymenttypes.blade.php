<?php
/***********************************************************
lstsrvpaymenttypes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 6, 2022
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2022

Page Description :

***********************************************************/
?>


 @foreach ( $lst_srv_paymenttypes as $key => $spt_info )
    <tr>
		<td></td>
		<td>{{ $spt_info->st_id }}</td> 
		<td>{{ $spt_info->PaymentType->pt_payment_type }}</td> 
		<td>{{ $spt_info->IncomeAccount->aa_account }} - {{ $spt_info->IncomeAccount->aa_account_label }}</td> 
		<td>{{ $spt_info->PurchaseAccount->aa_account }} - {{ $spt_info->PurchaseAccount->aa_account_label }}</td> 
		<td style="width:2px;"><a  data-st_id="{{ $spt_info->st_id }}"  href="#"  id="EDIT_SPT_{{ $spt_info->st_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td style="width:2px;"><a  data-st_id="{{ $spt_info->st_id }}"  href="#"  id="DELETE_SPT_{{ $spt_info->st_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
	</tr>
 @endforeach