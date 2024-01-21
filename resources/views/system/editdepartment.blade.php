<?php
/***********************************************************
editform.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Departments Management"])

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
<script type="text/javascript" src="{{ url('js/modules/departments.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/savedepartments.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Department</h3>
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
     <form name="frm_save_department" id="FORM_SAVE_DEPARTMENT">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="d_id" value="{{ $department_info->sd_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Department Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			 
                    <div class="row">
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Department Code <span class="required"> * </span></label>
                                <input type="text" name="sd_department_code" id="SD_DEPARTMENT_CODE" class="form-control" required="required" maxlength="10"  value="{{ $department_info->sd_department_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Department Name <span class="required"> * </span></label>
                                <input type="text" name="sd_department_title" id="SD_DEPARTMENT_TITLE" class="form-control" required="required" maxlength="100"  value="{{ $department_info->sd_department_title }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Department Order <span class="required"> * </span></label>
                                    <input type="number" name="sd_department_order" id="SD_DEPARTMENT_ORDER" class="form-control" required="required" step="1"  value="{{ $department_info->sd_department_order }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Parent Department</label>
                                  <select name="sd_parent_department" id="SD_PARENT_DEPARTMENT" class="form-control m-select2" style="width:100%">
                                        <option value="0">--Select One--</option>
                                        @foreach( $lst_departments as $key => $dep_info)
                                         <option {{ $department_info->sd_parent_department == $dep_info->sd_id ? "selected" : "" }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                        @endforeach 
                                    </select>
                            </div>
                        </div>
                          <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Department Manager</label>
                                  <select name="sd_department_manager" id="SD_DEPARTMENT_MANAGER" class="form-control m-select2" style="width:100%">
                                        <option value="0">--Select One--</option>
                                        @foreach( $lst_managers as $key => $man_info)
                                         <option {{ $department_info->sd_department_manager == $man_info->id ? "selected" : "" }} value="{{ $man_info->id }}">{{ $man_info->u_fullname }}</option>
                                        @endforeach 
                                    </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Department Color</label>
                                    <input type="color" name="sd_department_color" id="SD_DEPARTMENT_COLOR" class="form-control" value="{{ $department_info->sd_department_color }}" />
                                </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_department" id="BTN_SAVE_DEPARTMENT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection