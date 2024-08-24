<?php
/***********************************************************
addinboundcall.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Outbound Calls Management"])

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
<script type="text/javascript" src="{{ url('js/modules/outboundcalls.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/saveoutboundcall.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Outbound Call</h3>
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
    <form name="frm_save_outbound" id="FORM_SAVE_OUTBOUND">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Outbound Call Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Agent <span class="required"> * </span> </label>
                                     <select name="oc_agent_id" id="OC_AGENT_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Agent">
                                            <option value=""> -- Select Agent -- </option>
                                            @foreach($lst_agents as $key => $agent_info)
                                                    <option value="{{ $agent_info->id }}">{{ $agent_info->u_fullname }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Lead <span class="required"> * </span> </label>
                                     <select name="oc_lead_id" id="OC_LEAD_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Lead">
                                            <option value=""> -- Select Lead -- </option>
                                            @foreach($lst_leads as $key => $lead_info)
                                                    <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name }}&nbsp;{{ $lead_info->cl_last_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                       
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Call Subject <span class="required"> * </span></label><br/>
                                <input type="text" name="oc_call_subject" id="OC_CALL_SUBJECT" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Call Date <span class="required"> * </span></label><br/>
                                <input type="text" name="oc_call_date" required="required" id="OC_CALL_DATE" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Call From Time <span class="required"> * </span></label><br/>
                                <input type="text" name="oc_call_start_time" required="required" id="OC_CALL_START_TIME" class="form-control" value="" />
                             </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Call to Time <span class="required"> * </span></label><br/>
                                <input type="text" name="oc_call_end_time" required="required" id="OC_CALL_END_TIME" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" name="oc_follow_up_required" id="OC_FOLLOW_UP_REQUIRED"  value="1"  />
                                      <span class="form-check-label fw-semibold text-muted">
                                         Follow up Required
                                      </span>
                                  </label>  
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Follow up Date</label><br/>
                                <input type="text" name="oc_follow_up_date" required="required" id="OC_FOLLOW_UP_DATE" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Call Outcome</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="OC_CALL_OUTCOME"  class="form-control" name="oc_call_outcome"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Notes</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="OC_NOTES"  class="form-control" name="oc_notes"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_call" id="BTN_SAVE_CALL"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection