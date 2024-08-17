<?php
/***********************************************************
addform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Add New Cost center"])

@section('themes')
<style>
th {
	cursor: pointer;
}

#ModelPopUp {
	width: 800px;
}
</style>
@endsection 
@section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/costcenters.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/costcenters/savecostcenter.js') }}"></script>
@endsection 
@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Cost Center Management</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
		<form name="frm_save_costcenter" id="FORM_SAVE_COSTCENTER">
			<div class="form-body">
				<span id="hidden_fields"> {!! csrf_field() !!} </span>
				<div class="alert alert-success" style="display: none">
					<strong>Success!</strong> CostCenter Information is saved successfully!
				</div>
				<div class="alert alert-danger" style="display: none">
					<strong>Error!</strong> You have some form errors. Please check
					below.
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Cost center label <span class="required"> * </span></label>
							<input type="text" name="ac_cost_center_label" id="AC_COST_CENTER_LABEL" class="form-control" required="required" maxlength="255" value="" />
						</div>
					</div>
					<div class="col-md-4">
                                            <div class="form-group">
                                               <label class="control-label">Cost center Type</label>
                                                     <select  name="ac_type_id" id="AC_TYPE_ID" class="form-select" data-control="select2" data-placeholder="Select Costcenter Type">
                                                       <option value="">No Type</option>
                                                       @foreach ( $lst_types as $key => $type_info )
                                                               <option value="{{ $type_info->at_id }}">{{ $type_info->at_type_name }}</option>
                                                       @endforeach
                                               </select>
                                           </div>
                                       </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                               <label class="control-label">Cost center Category</label>
                                                     <select  name="ac_category_id" id="AC_CATEGORY_ID" class="form-select" data-control="select2" data-placeholder="Select Category">
                                                       <option value="">No Category</option>
                                                       @foreach ( $lst_categories as $key => $category_info )
                                                               <option value="{{ $category_info->cca_id }}">{{ $category_info->cca_category_name }}</option>
                                                       @endforeach
                                               </select>
                                           </div>
                                       </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                               <label class="control-label">Cost center Status</label>
                                                     <select  name="ac_status_id" id="AC_STATUS_ID" class="form-select" data-control="select2" data-placeholder="Select Status">
                                                       <option value="">No Status</option>
                                                       @foreach ( $lst_statuses as $key => $status_info )
                                                               <option value="{{ $status_info->cs_id }}">{{ $status_info->cs_status_name }}</option>
                                                       @endforeach
                                               </select>
                                           </div>
                                       </div>
                                    <div class="col-md-4">
                                            <div class="form-group">
                                               <label class="control-label">Cost center Manager</label>
                                                     <select  name="ac_manager_id" id="AC_MANAGER_ID" class="form-select" data-control="select2" data-placeholder="Select Manager">
                                                       <option value="">Select Manager</option>
                                                       @foreach ( $lst_managers as $key => $manager_info )
                                                               <option value="{{ $manager_info->id }}">{{ $manager_info->u_fullname }}</option>
                                                       @endforeach
                                               </select>
                                           </div>
                                       </div>
                                    <div class="col-md-4">
                                            <div class="form-group">
                                               <label class="control-label">Cost center Parent</label>
                                                     <select  name="ac_parent_cost_center_id" id="AC_PARENT_COST_CENTER_ID" class="form-select" data-control="select2" data-placeholder="Select Costcenter">
                                                       <option value="">Select Parent</option>
                                                       @foreach ( $lst_costcenters as $key => $costcenter_info )
                                                               <option value="{{ $costcenter_info->ac_id }}">{{ $costcenter_info->ac_cost_center_label }}</option>
                                                       @endforeach
                                               </select>
                                           </div>
                                       </div>
					<div class="col-md-12">
						<label class="control-label">Description</label><br/>
						<textarea style="width:100%;height:250px;" name="ac_cost_center_description" id="AC_COST_CENTER_DESCRIPTION" class="form-control"></textarea>
					</div>
				</div>
				<div class="row" style="height: 5px;"></div>
				<div class="row">
					<div class="col-md-9"></div>
					<div class="col-md-3" align="right">
						<button type="submit" name="btn_save_costcenter" id="BTN_SAVE_COSTCENTER" class="btn btn-info">Save</button>
						<button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection
