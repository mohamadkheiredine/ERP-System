<?php
/***********************************************************
displayunits.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>
<table id="PhoneUnitsDataTable" class="table m-table m-table--head-bg-brand" width="100%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="Units Package"> Units Package </th>
				<th title="Price"> Price </th>
				<th title="Currency">Currency</th>
				<th title="Edit"> Edit </th> 
				<th title="Delete"> Delete </th> 
			</tr>
		</thead>
		<tbody>
			  	@foreach($phone_units as $index => $pu_info)
                <tr  class="odd gradeX" data-pu_id="{{ $pu_info->pu_id }}">
                	<td><input type="checkbox" name="ck_pu_{{ $pu_info->pu_id }}" id="CK_PU_{{ $pu_info->pu_id }}" class="checkboxes" value="{{ $pu_info->pu_id }}" /></td>
                   <td>{{ $pu_info->pu_id }}</td>
                   <td>{{ $pu_info->pu_unit_label }}</td>
                   <td>{{ $pu_info->pu_unit_amount }}</td>
                   <td>{{ $pu_info->pu_currency_id }}</td>
                    <td><a href="#" data-pu_id="{{ $pu_info->pu_id }}" id="EDIT_PACK_{{ $pu_info->pu_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td> 
                    <td><a href="#" data-pu_id="{{ $pu_info->pu_id }}"   id="DELETE_PACK_{{ $pu_info->pu_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td> 
                </tr>
                @endforeach
		</tbody>
</table>