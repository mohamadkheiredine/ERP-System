<?php
/***********************************************************
addleadactivity.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 3, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

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

<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveactivity.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Activity</h3>
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
    <form name="frm_save_activity" id="FORM_SAVE_ACTIVITY">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Lead Activity Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Activity Lead <span class="required"> * </span> </label>
                                     <select class="bs-select form-control" required="required" name="fk_lead_id" id="FK_LEAD_ID" data-actions-box="true">
                                            <option value=""> -- Lead -- </option>
                                            @foreach($lst_leads as $key => $lead_info)
                                                    <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Contact <span class="required"> * </span> </label>
                                     <select class="bs-select form-control" required="required" name="fk_contact_id" id="FK_CONTACT_ID" data-actions-box="true">
                                            <option value=""> -- Contact -- </option>
                                            @foreach($lst_contacts as $key => $contact_info)
                                                    <option value="{{ $contact_info->cc_id }}">{{ $contact_info->cc_first_name . " " . $contact_info->cc_last_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Activity Type <span class="required"> * </span> </label>
                                     <select class="bs-select form-control" required="required" name="ca_activity_type" id="CA_ACTIVITY_TYPE" data-actions-box="true">
                                            <option value=""> -- Contact -- </option>
                                            @foreach($lst_activity_types as $key => $at_info)
                                                    <option value="{{ $at_info->at_id }}">{{ $at_info->at_activity_type }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Activity Purpose <span class="required"> * </span>  </label>
                                     <select class="bs-select form-control" required="required" name="ca_activity_purpose" id="CA_ACTIVITY_PURPOSE" data-actions-box="true">
                                            <option value=""> -- Contact -- </option>
                                            @foreach($lst_activity_purpose as $key => $ap_info)
                                                    <option value="{{ $ap_info->ap_id }}">{{ $ap_info->ap_purpose }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Activity Subject <span class="required"> * </span></label><br/>
                                <input type="text" name="ca_activity_subject" id="CA_ACTIVITY_SUBJECT" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Activity Owner <span class="required"> * </span> </label><br/>
                                <select class="bs-select form-control" required="required" name="fk_owner_id" id="FK_OWNER_ID" data-actions-box="true">
                                            <option value=""> -- Activity Owner -- </option>
                                            @foreach($lst_users as $key => $user_info)
                                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                            @endforeach
                                    </select>
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Activity Date <span class="required"> * </span></label><br/>
                                <input type="text" name="ca_activity_date" required="required" id="CA_ACTIVITY_DATE" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Activity Duration </label><br/>
                                <div class="row">
                                	<div class="col-md-4"><input type="number" min="0" max="23" step="1" name="ca_activity_duration_hours"  id="CA_ACTIVITY_DURATION_HOURS" class="form-control"  style="width:100px;"  value="00" /></div>
                                	<div class="col-md-2">
                                	&nbsp;:&nbsp;
                                	</div>
                                	<div class="col-md-4"><input type="number" min="0" max="59" step="1" class="form-control" name="ca_activity_duration_min" id="CA_ACTIVITY_DURATION_MIN" style="width:100px;" value="00" /></div>
                                	<div class="col-md-2"></div>
                                </div>
                             </div>
                        </div> 
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Activity Details</label><br/>
                                <ul class="ActivityDetails">
                                	<li>
                                		<label><input type="radio" checked="checked" name="activity_detail" value="1"  /> Current Activity</label>
                                	</li>
                                	<li>
                                		<label><input type="radio" name="activity_detail" value="2"  /> Completed Activity</label>
                                	</li>
                                	<li>
                                		<label><input type="radio" name="activity_detail" value="3"  /> Schedule Activity</label>
                                	</li>
                                </ul>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Activity Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CA_ACTIVITY_DESCRIPTION"  class="form-control" name="ca_activity_description"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Activity Result</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CA_ACTIVITY_RESULT"  class="form-control" name="ca_activity_result"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_activity" id="BTN_SAVE_ACTIVITY"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
@endsection