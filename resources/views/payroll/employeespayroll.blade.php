<?php
/***********************************************************
employeespayroll.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 12, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
?>


@extends('layouts.layout',['page_title' => "Employee PayRoll Management"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/payroll.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/users/payroll.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">PayRoll Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
		</span>
		<!--begin: Search Form -->
		<div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group row">
						<div class="col-md-4">
							<div class="d-flex align-items-center">
								<!--begin::Input group-->
								<div class="position-relative w-md-400px me-md-2">
									<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" tabindex="1" />
								</div>
								<!--end::Input group-->
							</div>
						</div>
						<div class="col-md-4">
                                                    <select name="fk_employee_id" id="FK_EMPLOYEE_ID"  class="form-select" data-control="select2" data-placeholder="Select Employee">
                                                        <option value="0"> -- Users -- </option>
                                                        @foreach ( $lst_users as $key => $user_info )
                                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                        @endforeach
                                                </select>
						</div>
						<div class="col-md-4"><input id="TS_DATE" name="ts_date" class="form-control" value="" readonly="readonly" maxlength="10" /></div>
						<div class="col-md-4">&nbsp;</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<a href="{{ url('sales/orders/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Order
							</span>
						</span>
					</a>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div id="LstPayRoll" class="table-responsive">
			<table class="table table-row-dashed table-row-gray-300 gy-7">
              <thead>
               	<tr class="fw-bold fs-6 text-gray-800">
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="User"> User </th>
    				<th title="Month"> Month </th>
    				<th title="Net total">  Net total </th>
    				<th title="edit"> edit </th>
    				<th title="delete"> Delete </th>
    			</tr>
              </thead>
              <tbody class="LstPayRollBody">
              </tbody>
             </table>
		
		
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-10 col-lg-10 col-xs-10" align="left">
				 <ul id="PayRollPagination" class="pagination-sm"></ul>
			</div>
			<div class="col-md-2 col-lg-2 col-xs-2" align="right">
				<a href="{{ url('payroll/employeespayroll/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								Generate PayRoll
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
 </div> 
@endsection