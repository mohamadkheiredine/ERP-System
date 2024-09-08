<?php
/***********************************************************
addcase.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 2, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 

Page Description :
{Enter page description Here}
***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Call Center Management"])

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
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/mcases.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/callcenter/savemaintenancecase.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Case Management</h3>
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
    <form name="frm_save_case" id="FORM_SAVE_CASE">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Maintenance Case Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Case Code <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_case_code" id="CC_CASE_CODE" maxlength="15" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Case Label <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_case_label" id="CC_CASE_LABEL" maxlength="255" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Agent <span class="required"> * </span> </label>
                                   <select name="cc_assigned_agent_id" id="CC_ASSIGNED_AGENT_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Assigned Agent">
                                          <option value=""> -- Select Agent -- </option>
                                          @foreach($lst_agents as $key => $agent_info)
                                                  <option value="{{ $agent_info->id }}">{{ $agent_info->u_fullname }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Technician <span class="required"> * </span> </label>
                                   <select name="cc_technician_id" id="CC_TECHNICIAN_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Technician">
                                          <option value=""> -- Select Agent -- </option>
                                          @foreach($lst_technicians as $key => $tech_info)
                                                  <option value="{{ $tech_info->id }}">{{ $tech_info->u_fullname }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Case Priority <span class="required"> * </span> </label>
                                     <select name="cc_priority_level" id="CC_PRIORITY_LEVEL"  class="form-control form-select" data-control="select2" data-placeholder="Select Priority Level">
                                            <option value=""> -- Select Priority Level -- </option> 
                                            <option value="low"> Low </option> 
                                            <option value="medium"> Medium </option> 
                                            <option value="high"> High </option> 
                                            <option value="urgent"> Urgent </option> 
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Case Status <span class="required"> * </span> </label>
                                   <select name="cc_case_status" id="CC_CASE_STATUS"   class="form-control form-select" data-control="select2" data-placeholder="Select Case Status">
                                          <option value=""> -- Select Status -- </option>
                                          @foreach($lst_statuses as $key => $status_info)
                                                  <option value="{{ $status_info->cc_id }}">{{ $status_info->cc_status_title }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                  <label class="control-label"> Case Inbound Call <span class="required"> * </span> </label>
                                   <select name="cc_call_id" id="CC_CALL_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Case Call">
                                          <option value=""> -- Select Call -- </option>
                                          @foreach($lst_inbound_calls as $key => $call_info)
                                                  <option value="{{ $call_info->ic_id }}">{{ $call_info->cc_case_label }}</option>
                                          @endforeach
                                  </select>
                              </div>
                        </div> 
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Case Deadline <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_case_deadline" required="required" id="CC_CASE_DEADLINE" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Case Resolution Date <span class="required"> * </span></label><br/>
                                <input type="text" name="cc_resolution_date" required="required" id="CC_RESOLUTION_DATE" class="form-control" value="" />
                             </div>
                        </div>  
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Case Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CC_CASE_DESCRIPTION"  class="form-control" name="cc_case_description"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Resolution Notes</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CC_RESOLUTION_NOTES"  class="form-control" name="cc_resolution_notes"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_case" id="BTN_SAVE_CASE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection