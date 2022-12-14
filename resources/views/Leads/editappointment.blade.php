<?php
/***********************************************************
editappointment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Leads Management > Edit Appointment"])

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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveappointments.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">

				</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-ellipsis-h m--font-brand"></i>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav">
											<li class="m-nav__section m-nav__section--first">
												<span class="m-nav__section-text">
													Quick Actions
												</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet__body">
             <form name="frm_save_appointment" id="FORM_SAVE_APPOINTMENT">
                <div class="form-body">
                     <span id="hidden_fields"> 
                        {!! csrf_field() !!} 
                        <input type="hidden" name="ca_id" value="{{ $appt_info->ca_id }}" />
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
                                                    <option {{ $appt_info->fk_lead_id == $lead_info->cl_id ? "selected" : "" }} value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Activity Subject <span class="required"> * </span></label><br/>
                                <input type="text" name="ca_appointment_subject" required="required" id="CA_APPOINTMENT_SUBJECT" class="form-control" value="{{ $appt_info->ca_activity_subject }}" />
                             </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Apppointment Assigned To <span class="required"> * </span> </label><br/>
                                <select class="bs-select form-control" required="required" name="fk_assigned_to" id="FK_ASSIGNED_ID" data-actions-box="true">
                                            <option value=""> -- Responsible User -- </option>
                                            @foreach($lst_users as $key => $user_info)
                                                    <option {{ $appt_info->fk_assigned_to == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                            @endforeach
                                    </select>
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Appointment Date <span class="required"> * </span></label><br/>
                                <input type="text" name="ca_appointment_date" required="required" id="CA_APPOINTMENT_DATE" class="form-control" value="{{ $appt_info->ca_appointment_date }}" />
                             </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> Start Time <span class="required"> * </span></label><br/>
                               <div class='input-group timepicker' id='m_timepicker_st'>
									<input type='text' required="required" class="form-control m-input" name="ca_appointment_start_time" value="{{ $appt_info->ca_appointment_start_time }}" readonly placeholder="Select time" type="text"/>
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
									<input type='text' required="required" class="form-control m-input" name="ca_appointment_end_time"  value="{{ $appt_info->ca_appointment_end_time }}" readonly placeholder="Select time" type="text"/>
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
                                <textarea style="width:100%;height:250px;resize:none" id="CA_APPOINTMENT_DESCRIPTION" required="required"  class="form-control" name="ca_appointment_description"  cols="">{{ $appt_info->ca_appointment_description }}</textarea>
                             </div>
                        </div>
                        <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label"> Appointment Result</label><br/>
                                <textarea style="width:100%;height:250px;resize:none" id="CA_APPOINTMENT_RESULT" required="required" class="form-control" name="ca_appointment_results"  cols="">{{ $appt_info->ca_appointment_results }}</textarea>
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