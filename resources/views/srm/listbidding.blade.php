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