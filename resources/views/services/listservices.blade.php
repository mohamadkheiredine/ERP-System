<?php
/***********************************************************
listservices.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 12, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
?>


<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
			<th title="#">#</th>
				<th title="Id" >ID</th>
				<th title="code">Service Code</th>
				<th title="Name">Service Name</th> 
				<th title="edit">edit</th>
				<th title="delete">Delete</th>
			</tr>
		</thead>
		<tbody>

		     @foreach ( $lst_services as $key => $service_info )
                <tr>
                    <td><input type="checkbox" name="ck_cs_{{ $service_info->cs_id }}" id="CK_CS_{{ $service_info->cs_id }}" class="checkboxes" value="{{ $service_info->cs_id }}" /></td>
    				<td>{{ $service_info->cs_id }}</td>
    				<td>{{ $service_info->cs_service_code }}</td>
    				<td>{{ $service_info->cs_service_title }}</td> 
    				 <td style="width:2px;"><a  data-cs_id="{{ $service_info->cs_id }}"  href="#"  id="EDIT_SERVICE_{{ $service_info->cs_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
                       <td style="width:2px;"><a  data-cs_id="{{ $service_info->cs_id }}"  href="#"  id="DELETE_SERVICE_{{ $service_info->cs_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    			</tr>
		     @endforeach

			</tbody>
</table>