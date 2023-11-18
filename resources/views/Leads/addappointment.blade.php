<?php
/***********************************************************
addappointment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Leads Management > Add New Appointment"])

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
<script type="text/javascript" src="{{ url('js/libraries/crm/saveappointments.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Appointment</h3>
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
    <form name="frm_save_appointment" id="FORM_SAVE_APPOINTMENT">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Lead Appointment Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>  
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Lead <span class="required"> * </span> </label>
                                     <select class="bs-select form-control" required="required" name="fk_lead_id" id="FK_LEAD_ID" data-actions-box="true">
                                            <option value=""> -- Lead -- </option>
                                            @foreach($lst_leads as $key => $lead_info)
                                                    <option {{ $lead_info->cl_id == $cl_id ? "selected" : "" }} value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Activity Subject <span class="required"> * </span></label><br/>
                                <input type="text" name="ca_appointment_subject" id="CA_APPOINTMENT_SUBJECT" class="form-control" value="" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Apppointment Assigned To <span class="required"> * </span> </label><br/>
                                <select class="bs-select form-control" required="required" name="fk_assigned_to" id="FK_ASSIGNED_ID" data-actions-box="true">
                                            <option value=""> -- Responsible User -- </option>
                                            @foreach($lst_users as $key => $user_info)
                                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                            @endforeach
                                    </select>
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Appointment Date <span class="required"> * </span></label><br/>
                                <input type="text" name="ca_appointment_date" required="required" id="CA_APPOINTMENT_DATE" class="form-control" value="" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Start Time <span class="required"> * </span></label><br/>
                               <div class='input-group timepicker' id='m_timepicker_st'>
									<input type='text' class="form-control m-input" id="ca_appointment_start_time" name="ca_appointment_start_time" readonly placeholder="Select time" type="text"/>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
									</div>
								</div>
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> End Time <span class="required"> * </span></label><br/>
                               <div class='input-group timepicker' id='m_timepicker_et'>
									<input type='text' class="form-control m-input" id="ca_appointment_end_time" name="ca_appointment_end_time" readonly placeholder="Select time" type="text"/>
									<div class="input-group-append">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
									</div>
								</div>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Appointment Description</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CA_APPOINTMENT_DESCRIPTION"  class="form-control" name="ca_appointment_description"  cols=""></textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Appointment Result</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CA_APPOINTMENT_RESULT"  class="form-control" name="ca_appointment_results"  cols=""></textarea>
                             </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_appointment" id="BTN_SAVE_APPOINTMENT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
 </div>

@endsection