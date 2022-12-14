<?php
/***********************************************************
lstcontacts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="m-datatable" id="ContactsDatatables" width="100%">
    		<thead>
    			<tr>
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="Contact Name"> Contact Name </th>
    				<th title="Email"> Email </th>
    				<th title="Phone"> Phone </th>
    				<th title="Mobile"> Mobile </th> 
    				<th title="Fax"> Fax </th> 
    				<th title="Edit"> Edit </th> 
    				<th title="Delete"> Delete </th> 
    			</tr>
    		</thead>
    		<tbody>
    			  	@foreach($lst_lead_contacts as $index => $contact_info)
                    <tr  class="odd gradeX" data-cc_id="{{ $contact_info->cc_id }}">
                    	<td><input type="checkbox" name="ck_cc_{{ $contact_info->cc_id }}" id="CK_CC_{{ $contact_info->cc_id }}" class="checkboxes" value="{{ $contact_info->cc_id }}" /></td>
                       <td>{{ $contact_info->cc_id }}</td>
                       <td>{{ $contact_info->cc_first_name . " " . $contact_info->cc_last_name }}</td>
                       <td>{{ $contact_info->cc_contact_email }}</td>
                       <td>{{ $contact_info->cc_contact_phone }}</td>
                       <td>{{ $contact_info->cc_contact_mobile }}</td>
                        <td>{{ $contact_info->cc_contact_fax }}</td> 
                        <td>
                        	 <a href="#"  data-cc_id="{{ $contact_info->cc_id }}" id="EDIT_CONTACT_{{ $contact_info->cc_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a>
                        </td> 
                        <td>
                        <a  data-cc_id="{{ $contact_info->cc_id }}" href="#"  id="DELETE_CONTACT_{{ $contact_info->cc_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
                        </td> 
                    </tr>
                    @endforeach
    		</tbody>
    </table>