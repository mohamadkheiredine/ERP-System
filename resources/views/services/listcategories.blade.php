<?php
/***********************************************************
listcategories.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id">ID</th>
				<th title="ref">Category ref</th>
				<th title="Name">Category Name</th>
				<th title="edit">edit</th>
				<th title="delete">Delete</th>
			</tr>
		</thead>
		<tbody>

		     @foreach ( $service_categories as $key => $category_info )
                <tr>
    				<td>{{ $category_info->sc_id }}</td>
    				<td>{{ $category_info->sc_category_ref }}</td>
    				<td>{{ $category_info->sc_category_name }}</td>
    				 <td style="width:2px;"><a  data-sc_id="{{ $category_info->sc_id }}"  href="#"  id="EDIT_CATEGORY_{{ $category_info->sc_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td>
                       <td style="width:2px;"><a  data-sc_id="{{ $category_info->sc_id }}"  href="#"  id="DELETE_CATEGORY_{{ $category_info->sc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    			</tr>
		     @endforeach

			</tbody>
</table>