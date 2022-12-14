<?php
/***********************************************************
displaylistservices.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display list of Services linked to the current lead 
***********************************************************/
?>

<div class="m_datatable" id="LstLeadServices">
<table class="m-datatable" id="ServicesDatatables" width="100%">
    		<thead>
    			<tr>
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="Service Name"> Service Name </th>
    				<th title="Nbr of Hours"> Service Nbr of Hours </th>
    				<th title="Cost"> Cost </th>
    				<th title="Price"> Price </th>
    				<th title="Edit"> Edit </th> 
    				<th title="Delete"> Delete </th> 
    			</tr>
    		</thead>
    		<tbody>
    			  	@foreach($LeadServices as $index => $li_info)
                    <tr  class="odd gradeX" data-ci_id="{{ $li_info->ci_id }}">
                    	<td><input type="checkbox" name="ck_ci_{{ $li_info->ci_id }}" id="CK_CI_{{ $li_info->ci_id }}" class="checkboxes" value="{{ $li_info->ci_id }}" /></td>
                       <td>{{ $li_info->ci_id }}</td>
                       <td>{{ $services_array[$li_info->fk_item_id]['cs_service_title'] }}</td>
                       <td>{{ $li_info->ci_item_nbr_of_hours }}</td>
                       <td>{{ $li_info->ci_total_cost }}</td>
                       <td>{{ $li_info->ci_total_price }}</td> 
                        <td><a href="#" data-ci_id="{{ $li_info->ci_id }}" id="EDIT_LITEM_{{ $li_info->ci_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td> 
                        <td><a href="#" data-ci_id="{{ $li_info->ci_id }}"   id="DELETE_LIEM_{{ $li_info->ci_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td> 
                    </tr>
                    @endforeach
    		</tbody>
    </table>
</div>
