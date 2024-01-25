<?php
/***********************************************************
listcontracts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

 @foreach( $lst_supplier_contracts as $key => $contract_info )
    <tr>
		<td>{{ $contract_info->sc_id }}</td>
		<td>{{ $contract_info->sc_contract_title }}</td>
		<td>{{ $contract_info->sc_contract_date }}</td>
		<td>{{ $contract_info->sc_total_price }}</td>
		<td>{{ isset( $currencies_array[ $contract_info->sc_currency_id ]) ? $currencies_array[ $contract_info->sc_currency_id ]['cc_currency_code'] . " - " .  $currencies_array[ $contract_info->sc_currency_id ]['cc_currency_name'] : "N/A" }}</td>
		 <td style="width:2px;"><a  data-sc_id="{{ $contract_info->sc_id }}"  href="#"  id="EDIT_CONTRACT_{{ $contract_info->sc_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
           <td style="width:2px;"><a  data-sc_id="{{ $contract_info->sc_id }}"  href="#"  id="DELETE_CONTRACT_{{ $contract_info->sc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
	</tr>
 @endforeach