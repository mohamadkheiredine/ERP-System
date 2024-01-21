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


$session_user_id = session()->get('user_id');
?>
  <?php  foreach ( $client_categories as $key => $category_info ) { ?>
        <tr>
			<td>
				{{ $category_info->cc_id }}
			</td>
			<td>
				{{ $category_info->cc_category_ref }}
			</td>
			<td>
				{{ $category_info->cc_category_name }}
			</td>
			 <td style="width:2px;">
               <a  data-cc_id="{{ $category_info->cc_id }}"  href="#"  id="EDIT_CATEGORY_{{ $category_info->cc_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a>
               </td>
               <td style="width:2px;">
                <a  data-cc_id="{{ $category_info->cc_id }}"  href="#"  id="DELETE_CATEGORY_{{ $category_info->cc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
              </td>
		</tr>
     <?php } ?>