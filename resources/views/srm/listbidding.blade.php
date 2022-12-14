<?php
/***********************************************************
listbidding.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="#"><input type="checkbox" name="ck_all_sb" id="CK_ALL_SB" class="checkboxes" value="1" /></th>
				<th title="Id"> ID </th>
				<th title="Bidding Ref"> Bidding Ref </th>
				<th title="Bidding Title"> Bidding Title </th>
				<th title="Start Date"> Start Date </th>
				<th title="End Date"> End Date </th>
				<th style="width:2px;" nowrap title="#"> edit </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_supplier_bidding  as $index => $sb_info)
                <tr  class="odd gradeX" data-sb_id="{{ $sb_info->sb_id }}">
                	<td><input type="checkbox" name="ck_sb_{{ $sb_info->sb_id }}" id="CK_SB_{{ $sb_info->sb_id }}" class="checkboxes" value="{{ $sb_info->sb_id }}" /></td>
                   <td>{{ $sb_info->sb_id }}</td>
                   <td>{{ $sb_info->sb_bidding_ref }}</td>
                   <td>{{ $sb_info->sb_bid_title }}</td>
                   <td>{{ $sb_info->sb_start_date }}</td> 
                   <td>{{ $sb_info->sb_end_date }}</td> 
                    <td><a href="#" data-sb_id="{{ $sb_info->sb_id }}" id="EDIT_BIDDING_{{ $sb_info->sb_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
                    <td><a href="#" data-sb_id="{{ $sb_info->sb_id }}"  id="DELETE_BIDDING_{{ $sb_info->sb_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>