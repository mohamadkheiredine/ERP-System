<?php
/***********************************************************
leadfiles.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 gy-7">
                            <thead>
                              <tr class="fw-bold fs-6 text-gray-800">
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="File Name"> File Name </th>
    				<th title="Attached By"> Attached By </th>
    				<th title="Date Added"> Date Added </th>
    				<th title="Size"> Size </th> 
    				<th title="Size"> options </th> 
    			</tr>
    		</thead>
    		<tbody>
    			  	@foreach($lst_files as $index => $lf_info)
                    <tr  class="odd gradeX" data-lf_id="{{ $lf_info->lf_id }}">
                    	<td><input type="checkbox" name="ck_lf_{{ $lf_info->lf_id }}" id="CK_LF_{{ $lf_info->lf_id }}" class="checkboxes" value="{{ $lf_info->lf_id }}" /></td>
                       <td>{{ $lf_info->lf_id }}</td>
                       <td>{{ $lf_info->lf_file_name . "." . $lf_info->lf_file_extension }}</td>
                       <td>{{ $users_array[ $lf_info->lf_uploaded_by]['u_fullname'] }}</td>
                       <td>{{ $lf_info->lf_uploaded_date }}</td>
                       <td>{{ $lf_info->lf_file_size }}</td>
                        <td>
                        	 
                        </td> 
                    </tr>
                    @endforeach
    		</tbody>
    </table>
                </div>
	</div>
</div>