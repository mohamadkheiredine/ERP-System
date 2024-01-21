<?php
/***********************************************************
activities.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 14, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
page of management for Activities of sales in this system
***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Leads Management"])

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

<script type="text/javascript" src="{{ url('js/modules/activities.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/activities.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Activities Management</h3>
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
	</span>
		<!--begin: Search Form -->
		<div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
						 <div class="d-flex align-items-center">
        											<!--begin::Input group-->
        											<div class="position-relative w-md-400px me-md-2">
        												<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
        													<span class="path1"></span>
        													<span class="path2"></span>
        												</i>
        												<input type="text" class="form-control form-control-solid ps-10" name="general_search" value="" placeholder="Search" />
        											</div>
        											<!--end::Input group-->
        										</div>

						</div>
						<div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="ACTIVITIES_LEAD" name="activities_lead">
                            			<option value="0">-- Select lead --</option>
                                        @foreach($lst_leads as $index => $lead_info)
                                          <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						<div class="col-md-4">
							<select class="bs-select form-control" id="ACTIVITIES_USER" name="activities_user">
                    			<option value="0">--All Users--</option>
                                @foreach($lst_users as $index => $user_info)
                                  <option {{ $user_info->id == session("user_id") ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ url('crm/addactivity') }}" class="btn btn-info">
						<span>
							<i class="flaticon-users"></i>
							<span>
								New Activity
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
		<div class="row">
			<div class="col-md-12" id="LstActivities">
			</div>			
		</div> 
    </div>
</div>



@endsection