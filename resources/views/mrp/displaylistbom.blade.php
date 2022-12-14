<?php
/***********************************************************
displaylistbom.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="html_table" width="50%">
		<thead>
			<tr>
				<th title="#">#</th>
				<th title="Id"> ID </th>
				<th title="BOM Code"> BOM Code </th>
				<th title="BOM Label"> BOM Label </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_bom  as $index => $bom_info)
                <tr  class="odd gradeX" data-bm_id="{{ $bom_info->bm_id }}">
                	<td><input type="checkbox" name="ck_bm_{{ $bom_info->bm_id }}" id="CK_BM_{{ $bom_info->bm_id }}" class="checkboxes" value="{{ $bom_info->bm_id }}" /></td>
                   <td>{{ $bom_info->bm_id }}</td>
                   <td>{{ $bom_info->bm_code }}</td> 
                   <td>{{ $bom_info->bm_label }}</td> 
                    <td><a href="#" data-bm_id="{{ $bom_info->bm_id }}" id="EDIT_BOM_{{ $bom_info->bm_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-bm_id="{{ $bom_info->bm_id }}"  id="DELETE_BOM_{{ $bom_info->bm_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>