<?php
/***********************************************************
listbiddingitems.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display List of Bidding Items
***********************************************************/

?>

<table class="mbp_datatable" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="#"><input type="checkbox" name="ck_all_sub_product" id="CK_ALL_SUB_PRODUCT" class="checkboxes" value="1" /></th>
				<th title="Id"> ID </th>
				<th title="Item Name"> Item Name </th>
				<th title="Item Quantity"> Item Quantity </th>
				<th style="width:2px;" nowrap title="#"> Delete </th>
			</tr>
		</thead>
		<tbody>
			  	@foreach($lst_bidding_products  as $index => $bp_info)
                <tr  class="odd gradeX" data-bi_id="{{ $bp_info->bi_id }}">
                	<td><input type="checkbox" name="ck_bi_{{ $bp_info->bi_id }}" id="CK_BI_{{ $bp_info->bi_id }}" class="checkboxes" value="{{ $bp_info->bi_id }}" /></td>
                   <td>{{ $bp_info->bi_id }}</td>
                   <td>{{ $bp_info->bi_item_title }}</td>
                   <td>{{ $bp_info->bi_item_quanity }}</td>
                    <td><a href="#" data-bi_id="{{ $bp_info->bi_id }}"  id="DELETE_ITEM_{{ $bp_info->bi_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
                </tr>
                @endforeach
		</tbody>
</table>