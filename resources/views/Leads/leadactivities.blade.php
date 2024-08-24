<?php
/***********************************************************
leadactivities.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 gy-7">
        <thead>
            <tr class="fw-bold fs-6 text-gray-800">
                <th title="#">#</th>
                <th title="Id"> ID </th>
                <th title="Contact Name"> Contact Name </th>
                <th title="Activity Type"> Activity Type </th>
                <th title="User Responsible"> User Responsible </th>
                <th title="Subject"> Subject </th>
                <th title="Edit"> Edit </th> 
                <th title="Delete"> Delete </th> 
            </tr>
        </thead>
        <tbody>
            @foreach($lst_activities as $index => $act_info)
            <tr  class="odd gradeX" data-ca_id="{{ $act_info->ca_id }}">
                <td><input type="checkbox" name="ck_ca_{{ $act_info->ca_id }}" id="CK_CA_{{ $act_info->ca_id }}" class="checkboxes" value="{{ $act_info->ca_id }}" /></td>
               <td>{{ $act_info->ca_id }}</td>
               <td>{{ $act_info->contacts->cc_first_name . " " . $act_info->contacts->cc_last_name }}</td>
               <td>{{ $act_info->activitytypes->at_activity_type }}</td>
               <td>{{ $act_info->users->u_fullname }}</td>
               <td>{{ $act_info->ca_activity_subject }}</td> 
                <td><a href="#" data-ca_id="{{ $act_info->ca_id }}" id="EDIT_ACTIVITY_{{ $act_info->ca_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td> 
                <td><a href="#" data-ca_id="{{ $act_info->ca_id }}"   id="DELETE_ACTIVITY_{{ $act_info->ca_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td> 
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
