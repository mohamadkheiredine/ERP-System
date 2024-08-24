<?php
/***********************************************************
lstappointments.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 5, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List of appointments related to selected Lead
***********************************************************/

?>

<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 gy-7">
        <thead>
            <tr class="fw-bold fs-6 text-gray-800">
                <th title="#">#</th>
                <th title="Id"> ID </th>
                <th title="subject"> Subject </th>
                <th title="date"> Date </th>
                <th title="starttime">Start Time</th>
                <th title="endtime"> End Time </th>
                <th title="Edit"> Edit </th> 
                <th title="Delete"> Delete </th> 
            </tr>
        </thead>
        <tbody>
            @foreach($LeadAppointments as $index => $appt_info)
            <tr  class="odd gradeX" data-ca_id="{{ $appt_info->ca_id }}">
                <td><input type="checkbox" name="ck_ca_{{ $appt_info->ca_id }}" id="CK_CA_{{ $appt_info->ca_id }}" class="checkboxes" value="{{ $appt_info->ca_id }}" /></td>
               <td>{{ $appt_info->ca_id }}</td>
               <td>{{ $appt_info->ca_appointment_subject }}</td>
               <td>{{ $appt_info->ca_appointment_date }}</td>
               <td>{{ $appt_info->ca_appointment_start_time }}</td>
               <td>{{ $appt_info->ca_appointment_end_time }}</td> 
                <td><a href="#" data-ca_id="{{ $appt_info->ca_id }}" id="EDIT_APPT_{{ $appt_info->ca_id }}" ><i class="fa fa-pencil-square-o" aria-hidden="true" height="16" ></i></a></td> 
                <td><a href="#" data-ca_id="{{ $appt_info->ca_id }}"   id="DELETE_APPT_{{ $appt_info->ca_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td> 
            </tr>
            @endforeach
        </tbody>
    </table>
</div>