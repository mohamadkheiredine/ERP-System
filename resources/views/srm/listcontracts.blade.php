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

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id"> ID </th>
				<th title="Contract Title"> Contract Title </th>
				<th title="Contract Date"> Contract Date </th>
				<th title="Contract Price"> Contract Price </th>
				<th title="Contract Currency"> Contract Currency </th>
				<th style="width:4px !important;" nowrap title="#">edit</th>
				<th style="width:4px !important;" nowrap title="#">Delete</th>
			</tr>
		</thead>
		<tbody>
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
			</tbody>
</table>