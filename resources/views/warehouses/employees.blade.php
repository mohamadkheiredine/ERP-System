<?php
/***********************************************************
employees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
$employees_info
***********************************************************/

?>


<div class="row">
	<div class="col-md-2 col-xs-2"></div>
	<div class="col-md-8 col-xs-8">
			<table class="table m-table m-table--head-bg-success">
				<thead>
					<tr>
						<th>
							#
						</th>
						<th>
							Username
						</th>
						<th>
							FullName
						</th>
						<th>
							Job Title
						</th>
						<th>
							
						</th>
					</tr>
				</thead>
				<tbody class="LstWarehouseZones">
				@foreach( $WareHouseEmployees as $index => $we_info )
					<tr  data-user_id="{{ $we_info->fk_employee_id }}">
						<td> {{ $we_info->fk_employee_id }} </td>
						<td> {{ $employees_info[ $we_info->fk_employee_id ]['user_name'] }} </td>
						<td> {{ $employees_info[ $we_info->fk_employee_id ]['full_name'] }} </td>
						<td> {{ $employees_info[ $we_info->fk_employee_id ]['job_title'] }} </td>
						<td> <a  href="#"  id="DELETE_EMPLOYEE_{{ $we_info->fk_employee_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="8" ></i></a> </td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="col-md-12" align="right">
				<button type="button" name="btn_add_employee" id="BTN_ADD_EMPLOYEE" data-toggle="modal" data-target="#EmployeeModel" class="btn m-btn m-btn--gradient-from-primary m-btn--gradient-to-info"> Add Employee </button>
			</div>
	</div>
	<div class="col-md-2 col-xs-2"></div>
</div>