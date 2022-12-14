<?php
/***********************************************************
listshipcompanies.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 12, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="User Id">
					ID
				</th>
				<th title="Company Name">
					Company Name
				</th>
				<th title="Company Number">
					Company Number
				</th>
				<th title="Company Currency">
					Company Currency
				</th>
				<th title="Company Rate">
					Company Rate
				</th>
				<th style="width:4px;" nowrap title="#">
					edit
				</th>
				<th style="width:4px;" nowrap title="#">
					Delete
				</th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_shipcompanies as $index => $comp_info)
                <tr  class="odd gradeX" data-sc_id="{{ $comp_info->sc_id }}">
                	<td><input type="checkbox" name="ck_sc_{{ $comp_info->sc_id }}" id="CK_SC_{{ $comp_info->sc_id }}" class="checkboxes" value="{{ $comp_info->sc_id }}" /></td>
                   <td>{{ $comp_info->sc_id }}</td>
                   <td>{{ $comp_info->sc_company_name }}</td>
                   <td>{{ $comp_info->sc_company_phone }}</td>
                   <td>{{ $comp_info->sc_company_currency }}</td>
                    <td><a href="#" data-sc_id="{{ $comp_info->sc_id }}" id="EDIT_SHIPCOMPANY_{{ $comp_info->sc_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-sc_id="{{ $comp_info->sc_id }}"  id="DELETE_SHIPCOMPANY_{{ $comp_info->sc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>