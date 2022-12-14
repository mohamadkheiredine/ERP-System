<?php
/***********************************************************
addleadcontact.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Leads Management > Add New Lead"])

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
<script type="text/javascript" src="{{ url('js/modules/contacts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/saveleadcontact.js') }}"></script>
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
             <form name="frm_save_contact" id="FORM_SAVE_CONTACT">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Contact Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Owner <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="fk_owner_id" id="FK_OWNER_ID" data-actions-box="true">
                                        <option value="">-- Select Owner --</option>
                                        <?php foreach ( $lst_users as $key => $user_info ) { ?>
                                                <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">First Name <span class="required"> * </span></label>
                                <input type="text" name="cc_first_name" id="CC_FIRST_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Last Name <span class="required"> * </span></label>
                                <input type="text" name="cc_last_name" id="CC_LAST_NAME" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Lead</label>
                                <select class="bs-select form-control" name="fk_lead_id" id="FK_LEAD_ID" data-actions-box="true">
                                        <option value="">-- Select Lead --</option>
                                        @foreach ( $lst_leads as $key => $lead_info )
                                                <option {{ $lead_info->cl_id == $cl_id ? "selected" : "" }}  value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Title</label>
                                <input type="text" name="cc_contact_title" id="CC_CONTACT_TITLE" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Email <span class="required"> * </span></label>
                                <input type="email" name="cc_contact_email" id="CC_CONTACT_EMAIL" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Department  </label>
                                <select class="bs-select form-control" name="cc_contact_department" id="FK_CONTACT_DEPARTMENT" data-actions-box="true">
                                        <option value="">-- Select Department --</option>
                                        <?php foreach ( $lst_departments as $key => $dep_info ) { ?>
                                                <option value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Contact Phone <span class="required"> * </span></label>
                                <input type="text" name="cc_contact_phone" id="CC_CONTACT_PHONE" class="form-control" required="required" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Home Phone </label>
                                <input type="text" name="cc_contact_home_phone" id="CC_CONTACT_HOME_PHONE" class="form-control" required="required" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Other Phone </label>
                                <input type="text" name="cc_contact_other_phone" id="CC_CONTACT_OTHER_PHONE" class="form-control" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Fax</label>
                                <input type="text" name="cc_contact_fax" id="CC_CONTACT_FAX" class="form-control" required="required" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Mobile <span class="required"> * </span></label>
                                <input type="text" name="cc_contact_mobile" id="CC_CONTACT_MOBILE" class="form-control" required="required" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Date Of Birth</label>
                                 <input type="text" name='cc_contact_dob' class="form-control" id="CC_CONTACT_DOB" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Assistant </label>
                                <input type="text" name="cc_contact_assistant" id="CC_CONTACT_ASSISTANT" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Assistant Phone</label>
                                <input type="text" name="cc_contact_asst_phone" id="CC_CONTACT_ASST_PHONE" class="form-control" maxlength="25"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Skype Id</label>
                                <input type="text" name="cc_skype_id" id="CC_SKYPE_ID" class="form-control" maxlength="45"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Secondary Email</label>
                                <input type="email" name="cc_second_email" id="CC_SECOND_EMAIL" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                              <div class="form-group">
                                <label>Reporting To </label>
                                <select class="bs-select form-control" name="cc_reporting_to" id="CC_REPORTING_TO" data-actions-box="true">
                                        <option value="">-- Select User --</option>
                                        @foreach( $lst_users as $key => $user_info )
                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Twitter Id</label>
                                <input type="text" name="cc_twitter_id" id="CC_TWITTER_ID" class="form-control"  maxlength="45"  value="" />
                            </div>
                        </div>
                   </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                    	<div class="col-md-12" align="left">
                		<label>Contact Image </label>
                        	</div>
                             <div class="col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="AVATAR_PIC" width="100" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
        
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="clearfix margin-top-10">
                                    <div>
                                        <span class="btn default btn-file" style="text-align: left;">
                                            <span class="fileinput-new"> Select image </span><br/>
                                            <input type="file" name="cc_avatar_pic" id="CC_AVATAR_PIC" /> </span>
                                    </div>
                                    <br>
                                    <span class="label label-danger"> NOTE! </span><br><br>
                                    <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                </div>
                            </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Mailing Address </label>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Country</label>
                                <select class="bs-select form-control" name="cc_mailing_country" id="CC_MAILING_COUNTRY" data-actions-box="true">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="cc_mailing_city" id="CC_MAILING_CITY" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="cc_mailing_state" id="CC_MAILING_STATE" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="cc_mailing_code" id="CC_MAILING_CODE" class="form-control" maxlength="10" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="cc_mailing_street" id="CC_MAILING_NAME" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                     </div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Other Address </label>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Country</label>
                                <select class="bs-select form-control" name="cc_other_country" id="CC_OTHER_COUNTRY" data-actions-box="true">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="cc_other_city" id="CC_OTHER_CITY" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="cc_other_state" id="CC_OTHER_STATE" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="cc_other_code" id="CC_OTHER_CODE" class="form-control" maxlength="10" value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="cc_other_street" id="CC_OTHER_NAME" class="form-control" maxlength="255" value="" />
                            </div>
                        </div>
                     </div> 
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_contact" id="BTN_SAVE_CONTACT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection