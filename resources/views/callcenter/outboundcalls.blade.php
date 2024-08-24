<?php
/***********************************************************
outboundcalls.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
Cost Center Categories Management 
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Outbound Call Management"])

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
<script type="text/javascript" src="{{ url('js/modules/outboundcalls.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/outboundcalls.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Outbound Calls Management</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
                                    <li><a class="dropdown-item" data-action_type="CREATE_LEAD" href="#" data-bs-toggle="modal" data-bs-target="#ModalCreateLead" >Create Lead</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
		<span id="hidden_fields"> 
			<input type="hidden" name="page_number" value="1" />
		</span>
		<!--begin: Search Form -->
		<div class="form">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
								<label>&nbsp;</label>
							<div class="d-flex align-items-center">
								<!--begin::Input group-->
								<div class="position-relative w-md-400px me-md-2">
									<i
										class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
										<span class="path1"></span> <span class="path2"></span>
									</i> <input type="text"
										class="form-control form-control-solid ps-10"
										name="general_search" id="generalSearch" value=""
										placeholder="Search" />
								</div>
								<!--end::Input group-->
							</div>

						</div>
						<div class="col-md-4">
                                                    <label class="control-label">Agent</label>
                                                    <select name="oc_agent_id" id="OC_AGENT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Agent">
                                                           <option value="">All Agents</option>
                                                           @foreach ( $lst_users as $key => $user_info )
                                                                   <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                           @endforeach
                                                   </select>
						</div>
						<div class="col-md-4">
                                                    
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<a href="{{ url('/callcenter/outboundcall/addform') }}"
						class="btn btn-info"> <span> <i class="flaticon-grid-menu-v2"></i>
							<span> New Call </span>
					</span>
					</a>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
		<!--begin: Datatable -->
		<div class="table-responsive">
			<table class="table table-striped gy-7 gs-7">
				<thead>
					<tr
						class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
						<th style="width: 2px;">#</th>
						<th style="width: 2px;">ID</th>
						<th>Agent</th>
						<th>Lead</th>
						<th>Date</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th style="width: 2px;white-space: nowrap;">edit</th>
						<th style="width: 2px;white-space: nowrap;">Delete</th>
					</tr>
				</thead>
				<tbody class="LstOutboundCalls" id="LstOutboundCalls"></tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-10" align="left">
				<ul id="OutboundCallsPagination" class="pagination-sm"></ul>
			</div>
			<div class="col-md-2" align="right"></div>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-xl-8 order-1 order-xl-1 align-right"></div>
			<div class="col-xl-2 order-2 order-xl-2 align-right">
				 
			</div>
			<div class="col-xl-2 order-3 order-xl-3 align-right">
				<a href="{{ url('/callcenter/outboundcall/addform') }}"
					class="btn btn-info"> <span> <i class="flaticon-grid-menu-v2"></i>
						<span> New Call </span>
				</span>
				</a> 
			</div>
		</div>
	</div>
</div>
<div class="modal fade"  tabindex="-1" id="ModalCreateLead" style="position: absolute;left:4%">
    <div class="modal-dialog">
        <div class="modal-content"  style="width:800px">
            <div class="modal-header">
                <h3 class="modal-title">Create Lead</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body" >
               <form name="frm_quick_lead" id="FRM_QUICK_LEAD">
               <span id="hidden_fields">
               	{!! csrf_field() !!}
               </span>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Lead Code</label>
                            <input type="text" maxlength="20" name="cl_lead_code" id="CL_LEAD_CODE" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">First Name <span class="required"> * </span></label>
                            <input type="text" maxlength="500" name="cl_first_name" id="CL_FIRST_NAME" class="form-control" value="" />
                        </div>
                    </div>
                     <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Last Name <span class="required"> * </span></label>
                            <input type="text" maxlength="500" name="cl_last_name" id="CL_LAST_NAME" class="form-control" value="" />
                        </div>
                    </div>
                     <div class="col-md-4">
                        <label class="control-label">Salesman</label>
                        <select name="cl_salesman_id" id="CL_SALESMAN_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Salesman">
                               <option value="">select Salesman</option>
                               @foreach ( $lst_users as $key => $user_info )
                                       <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                               @endforeach
                       </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" maxlength="255" name="cl_email" id="CL_EMAIL" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                         <div class="form-group">
                            <label class="control-label">Date Of Birth </label>
                             <input type="text" name='cl_date_birth' class="form-control" id="CL_DATE_BIRTH" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Phone</label>
                            <input type="text" maxlength="20" name="cl_phone" id="CL_PHONE" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Mobile</label>
                            <input type="text" maxlength="20" name="cl_mobile" id="CL_MOBILE" class="form-control" value="" />
                        </div>
                    </div>
                     <div class="col-md-4">
                        <label class="control-label">Agent</label>
                        <select name="cl_agent_id" id="CL_AGENT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Agent">
                               <option value="">select Agents</option>
                               @foreach ( $lst_users as $key => $user_info )
                                       <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                               @endforeach
                       </select>
                    </div>
                     <div class="col-md-4">
                        <label class="control-label">Reffered by</label>
                        <select name="cl_customer_id" id="CL_CUSTOMER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Customer">
                               <option value="">select Customer</option>
                               @foreach ( $lst_customers_info as $key => $customer_info )
                                       <option value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                               @endforeach
                       </select>
                    </div>
                    <div class="col-md-12" style="text-align: right"> 
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="BTN_QUICK_LEAD" name="btn_quick_lead" >Save changes</button>
                    </div> 
                </div>
               </form>
            </div>

            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

@endsection