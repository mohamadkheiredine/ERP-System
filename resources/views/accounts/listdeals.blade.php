<?php
/***********************************************************
listdeals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List Deals
***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id"> ID </th>
				<th title="Deal ref"> Deal ref </th>
				<th title="Deal Title"> Deal Title </th>
				<th title="Account Name"> Account Name </th>
				<th title="Account Name"> Deal Amount </th>
				<th style="width:4px !important;" nowrap title="#">edit</th>
				<th style="width:4px !important;" nowrap title="#">Delete</th>
			</tr>
		</thead>
		<tbody>
		     @foreach( $lst_account_deals as $key => $ad_info )
                <tr>
    				<td>{{ $ad_info->ad_id }}</td>
    				<td>{{ $ad_info->ad_deal_code }}</td>
    				<td>{{ $ad_info->ad_deal_title }}</td>
    				<td>{{ $accounts_array[ $ad_info->fk_account_id ] }}</td>
    				<td>{{ $ad_info->ad_deal_amount }}</td>
    				 <td style="width:2px;">
                       <a  data-ad_id="{{ $ad_info->ad_id }}"  href="#"  id="EDIT_DEAL_{{ $ad_info->ad_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a>
                       </td>
                       <td style="width:2px;">
                        <a  data-ad_id="{{ $ad_info->ad_id }}"  href="#"  id="DELETE_DEAL_{{ $ad_info->ad_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
                      </td>
    			</tr>
		     @endforeach
			</tbody>
</table>