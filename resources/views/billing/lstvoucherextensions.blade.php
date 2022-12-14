<?php
/***********************************************************
listvoucherextensions.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Apr 18, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/

?>

@foreach($list_voucher_extensions as $ve_index => $ve_info)
<tr class="extrow" data-ve_id="{{ $ve_info->ve_id }}">
	<td style="width:2px;">#</td>
	<td style="width:2px;">{{ $ve_info->ve_id }}</td>
	<td>{{ $ve_info->Account->aa_account . " - " . $ve_info->Account->aa_account_label }}</td>
	<td>{{ $ve_info->ve_extension_amount }}</td>
	<td>{{ $ve_info->currency->cc_currency_code }}</td>
	<td>{{ $ve_info->ve_extension_notes }}</td>
	<td style="width:4px;white-space: nowrap;text-align: center"><a href="#"  id="EDIT_VE_{{ $ve_info->ve_id }}" data-ve_id="{{ $ve_info->ve_id }}" class="EditExtRow" ><i class="fas fa-edit" height="16"></i></a></td>
	<td style="width:4px;white-space: nowrap;text-align: center"><a href="#" class="DeleteExtRow" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach