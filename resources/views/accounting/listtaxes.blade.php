<?php
/***********************************************************
listtaxes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 25, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Datasheet list of Taxes apply to this software
***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id">ID</th>
				<th title="Code">Code</th>
				<th title="Value">Value</th>
				<th title="Sale Account">Sale Account</th>
				<th title="Purchase Account">Purchase Account</th>
				<th style="width:4px !important;" nowrap title="#">edit</th>
				<th style="width:4px !important;" nowrap title="#">Delete</th>
			</tr>
		</thead>
		<tbody>

		     @foreach ( $lst_vat_accounts as $key => $vat_info )
                <tr>
    				<td>{{ $vat_info->av_id }}</td>
    				<td>{{ $vat_info->av_vat_code }}</td>
    				<td>{{ $vat_info->av_vat_rate }}</td>
    				<td>{{ $accounts_array[ $vat_info->av_sale_account_code ]['aa_account'] . " - " . $accounts_array[ $vat_info->av_sale_account_code ]['aa_account_label'] }}</td>
    				<td>{{ $accounts_array[ $vat_info->av_purchase_account_code ]['aa_account'] . " - " . $accounts_array[ $vat_info->av_purchase_account_code ]['aa_account_label'] }}</td>
    				<td style="width:2px;"><a  data-av_id="{{ $vat_info->av_id }}"  href="#"  id="EDIT_VAT_{{ $vat_info->av_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
                    <td style="width:2px;"><a  data-av_id="{{ $vat_info->av_id }}"  href="#"  id="DELETE_VAT_{{ $vat_info->av_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    			</tr>
		     @endforeach

			</tbody>
</table>