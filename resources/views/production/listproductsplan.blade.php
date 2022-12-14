<?php
/***********************************************************
listproductsplan.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 18, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>

<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th>ID</th>
			<th>Product Name</th>
			<th>Quanity</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		@foreach($lst_plan_items as $index => $item_info)
            <tr  class="odd gradeX" data-pi_id="{{ $item_info->pi_id }}">
            	<td><input type="checkbox" name="ck_pi_{{ $item_info->pi_id }}" id="CK_PI_{{ $item_info->pi_id }}" class="checkboxes" value="{{ $item_info->pi_id }}" /></td>
               <td>{{ $item_info->pi_id }}</td>
               <td>{{ $item_info->pi_item_label }}</td>
               <td>{{ $item_info->pi_item_quanity }}</td>
              <td style="width:2px;"> <a href="#"  data-pi_id="{{ $item_info->pi_id }}"  id="DELETE_ITEM_{{ $item_info->pi_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
            </tr>
            @endforeach
	</tbody>
</table>