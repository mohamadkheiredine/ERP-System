<?php
/***********************************************************
lstdaytypes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 5, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="table" id="html_table" width="100%">
		<thead>
			<tr class="fw-bold fs-6 text-gray-800">
				<th title="Id" style="width:50px;white-space: nowrap;">
					ID
				</th>
				<th title="ref" style="width:50px;white-space: nowrap;">
					Day Type
				</th>
				<th title="Name" style="width:50px;white-space: nowrap;">
					Day Color
				</th>
				<th style="width:2px !important;" nowrap title="#">
					edit
				</th>
				<th style="width:2px !important;" nowrap title="#">
					Delete
				</th>
			</tr>
		</thead>
		<tbody>

		     <?php  foreach ( $lst_day_types as $key => $dt_info ) { ?>
                <tr>
    				<td>
    					{{ $dt_info->dt_id }}
    				</td>
    				<td>
    					{{ $dt_info->dt_day_type }}
    				</td>
    				<td>
    				<div style="width:100%;height: 100%;background-color:{{ $dt_info->dt_day_color }};color:black;">
    					{{ $dt_info->dt_day_color }}
    				</div>
    				</td>
    				 <td style="width:2px;">
                       <a  data-dt_id="{{ $dt_info->dt_id }}"  href="#"  id="EDIT_DAYTYPE_{{ $dt_info->dt_id }}" ><i class="fa-solid fa-pen-to-square"></i></a>
                       </td>
                       <td style="width:2px;">
                       @if($dt_info->dt_line_code == 0 )
                        <a  data-dt_id="{{ $dt_info->dt_id }}"  href="#"  id="DELETE_DAYTYPE_{{ $dt_info->dt_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
                        @endif
                      </td>
    			</tr>
		     <?php } ?>

			</tbody>
</table>