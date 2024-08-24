<?php
/***********************************************************
editinboundcall.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 18, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Inbound Calls Management"])

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
<script type="text/javascript" src="{{ url('js/modules/inboundcalls.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/saveinboundcall.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Inbound Call</h3>
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
    <form name="frm_save_inbound" id="FORM_SAVE_INBOUND">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!}
                        <input type="hidden" name="ic_id" value="{{ $inboundcall_info->ic_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Inbound Call Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Agent <span class="required"> * </span> </label>
                                     <select name="fk_agent_id" id="FK_AGENT_ID"   class="form-control form-select" data-control="select2" data-placeholder="Select Agent">
                                            <option value=""> -- Select Agent -- </option>
                                            @foreach($lst_agents as $key => $agent_info)
                                                    <option {{ $inboundcall_info->fk_agent_id == $agent_info->id ? "selected" : "" }} value="{{ $agent_info->id }}">{{ $agent_info->u_fullname }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                         <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Customer <span class="required"> * </span> </label>
                                     <select name="fk_customer_id" id="FK_CUSTOMER_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Customer">
                                            <option value=""> -- Select Customer -- </option>
                                            @foreach($lst_customers as $key => $customer_info)
                                                    <option {{ $inboundcall_info->fk_customer_id == $customer_info->ic_id ? "selected" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                       
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Call Subject <span class="required"> * </span></label><br/>
                                <input type="text" name="ic_call_subject" id="IC_CALL_SUBJECT" class="form-control" value="{{ $inboundcall_info->ic_call_subject }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Call Date <span class="required"> * </span></label><br/>
                                <input type="text" name="ic_call_date" required="required" id="IC_CALL_DATE" class="form-control" value="{{ $inboundcall_info->ic_call_date }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Call From Time <span class="required"> * </span></label><br/>
                                <input type="text" name="ic_call_start_time" required="required" id="IC_CALL_START_TIME" class="form-control" value="{{ $inboundcall_info->ic_call_start_time }}" />
                             </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Call to Time <span class="required"> * </span></label><br/>
                                <input type="text" name="ic_call_end_time" required="required" id="IC_CALL_END_TIME" class="form-control" value="{{ $inboundcall_info->ic_call_end_time }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                      <input class="form-check-input" type="checkbox" name="ic_issue_resolved" {{ $inboundcall_info->ic_issue_resolved == 1 ? "checked" : "" }} id="IC_ISSUE_RESOLVED"  value="1"  />
                                      <span class="form-check-label fw-semibold text-muted">
                                         Issue Resolved
                                      </span>
                                  </label>  
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Call Outcome</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_CALL_OUTCOME"  class="form-control" name="ic_call_outcome"  cols="">{{ $inboundcall_info->ic_call_outcome }}</textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Notes</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="IC_NOTES"  class="form-control" name="ic_notes"  cols="">{{ $inboundcall_info->ic_notes }}</textarea>
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