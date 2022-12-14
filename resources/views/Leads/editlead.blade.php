<?php
/***********************************************************
editlead.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
{
    $image_src_url  = url('/')."/".Config::get('constants.CRM_PATH').$lead_info->cl_image_base_src.$lead_info->cl_image_file_name.".".$lead_info->cl_image_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.CRM_PATH').$lead_info->cl_image_base_src.$lead_info->cl_image_file_name.".".$lead_info->cl_image_extension;
    if(strlen($lead_info->cl_image_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}

?>
@extends('layouts.layout',['page_title' => "Leads Management" ])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}

</style>
<link rel="stylesheet" type="text/css" href="{{ url('default/assets/plugins/jquery-comments/css/jquery-comments.css') }}" />
	<link rel="stylesheet" href="{{ url('default/assets/plugins/scheduler/codebase/dhtmlxscheduler_material.css?v=5.2.2') }}" type="text/css" charset="utf-8">
@endsection
@section('plugins')

	<script src="{{ url('default/assets/plugins/scheduler/codebase/dhtmlxscheduler.js?v=5.2.2') }}" type="text/javascript" charset="utf-8"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('default/assets/plugins/jquery-comments/js/jquery-comments.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/contacts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/savelead.js') }}"></script>




@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">{{ "Manage Lead " .  $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</h3>
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
											<li class="m-nav__item">
												<a data-action_type="CONVERT_TO_ACCOUNT"  href="#" class="m-nav__link quickactions">
													 <i class="m-nav__link-icon flaticon-business"></i>
													<span class="m-nav__link-text">
														Convert to Account
													</span>
												</a>
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
             <form name="frm_save_lead" id="FORM_SAVE_LEAD">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="cl_id" id="CL_ID" value="{{ $lead_info->cl_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Lead Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Lead Owner</label><br/>
                                <select class="bs-select form-control" name="fk_lead_owner" id="FK_LEAD_OWNER" data-actions-box="true">
                                        <option value="">-- Select Owner --</option>
                                        <?php foreach ( $lst_users as $key => $user_info ) { ?>
                                                <option {{ $user_info->id == $lead_info->fk_lead_owner ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Company <span class="required"> * </span></label>
                                <input type="text" name="cl_company_name" id="CL_COMPANY_NAME" class="form-control" required="required" maxlength="255"  value="{{ $lead_info->cl_company_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">First Name <span class="required"> * </span></label>
                                <input type="text" name="cl_first_name" id="CL_FIRST_NAME" class="form-control" required="required" maxlength="255"  value="{{ $lead_info->cl_first_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Last Name <span class="required"> * </span></label>
                                <input type="text" name="cl_last_name" id="CL_LAST_NAME" class="form-control" required="required" maxlength="255"  value="{{ $lead_info->cl_last_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Email</label>
                                <input type="text" name="cl_email" id="CL_EMAIL" class="form-control" maxlength="255"  value="{{ $lead_info->cl_email }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Phone</label>
                                <input type="text" name="cl_phone" id="CL_PHONE" class="form-control" maxlength="25"  value="{{ $lead_info->cl_phone }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Mobile</label>
                                <input type="text" name="cl_mobile" id="CL_MOBILE" class="form-control" maxlength="25"  value="{{ $lead_info->cl_mobile }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Fax</label>
                                <input type="text" name="cl_fax" id="CL_FAX" class="form-control" maxlength="25"  value="{{ $lead_info->cl_fax }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Website</label>
                                <input type="text" name="cl_website" id="CL_WEBSITE" class="form-control" maxlength="255"  value="{{ $lead_info->cl_website }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Lead Source <span class="required"> * </span></label>
                                <select class="bs-select form-control" required="required" name="fk_lead_source" id="FK_LEAD_SOURCE" data-actions-box="true">
                                        <option value="">-- Select Owner --</option>
                                        @foreach ($lst_lead_source as $ls_index => $ls_info )
                                                <option {{ $ls_info->ls_id == $lead_info->fk_lead_source_id ? "selected" : "" }} value="{{ $ls_info->ls_id }}">{{ $ls_info->ls_lead_source }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Item Types<span class="required"> * </span></label>
                                <select class="bs-select form-control" required="required" name="cl_type_items" id="CL_TYPE_ITEMS" data-actions-box="true">
                                        <option value="">-- item types --</option>
                                        <option {{ $lead_info->cl_type_items == "1" ? "selected" : "" }} value="1">Products</option>
                                        <option {{ $lead_info->cl_type_items == "2" ? "selected" : "" }} value="2">Services</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Lead Status <span class="required"> * </span></label>
                                <select class="bs-select form-control" required="required" name="fk_lead_status_id" id="FK_LEAD_STATUS_ID"  data-actions-box="true">
                                        <option value="">-- Select Status --</option>
                                        @foreach ($lead_statuses as $ls_index => $ls_info )
                                                <option {{ $ls_info->ls_id == $lead_info->fk_lead_status_id ? "selected" : "" }} value="{{ $ls_info->ls_id }}">{{ $ls_info->ls_status_title }}</option>
                                        @endforeach
                                </select>
                                <input type="hidden" name="ini_status_id" value="{{ $lead_info->fk_lead_status_id }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Industry <span class="required"> * </span></label>
                                <select class="bs-select form-control" name="fk_industry_id" id="FK_INDUSTRY_ID" required="required" data-actions-box="true">
                                        <option value="">-- Select Industry --</option>
                                        @foreach ($lst_industries as $ind_index => $ind_info )
                                                <option {{ $ind_info->si_id == $lead_info->fk_industry_id ? "selected" : "" }}  value="{{ $ind_info->si_id }}">{{ $ind_info->si_industry }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Lead Assign To <span class="required"> * </span> </label>
                                <select class="bs-select form-control" name="fk_assign_to" required="required" id="FK_ASSIGN_TO" data-actions-box="true">
                                        <option value="">-- Select User --</option>
                                        <?php foreach ( $lst_users as $key => $user_info ) { ?>
                                                <option {{ $user_info->id == $lead_info->fk_assign_to ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                        <?php  } ?>
                                </select>
                                <input type="hidden" name="ini_assign_to" value="{{ $lead_info->fk_assign_to }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Number of Employees</label>
                                <input type="number" step="1" name="cl_nbr_employees" id="CL_NBR_EMPLOYEES" class="form-control"  value="{{ $lead_info->cl_nbr_employees }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Anual Revenue</label>
                                <input type="text" name="cl_anual_revenue" id="CL_ANUAL_REVENUE" class="form-control"  value="{{ $lead_info->cl_anual_revenue }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Skype Id</label>
                                <input type="text" name="cl_skype_id" id="CL_SKYPE_ID" class="form-control" maxlength="50" value="{{ $lead_info->cl_skype_id }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Twitter Account</label>
                                <input type="text" name="cl_twitter_account" id="CL_TWITTER_ACCOUNT" class="form-control" maxlength="50" value="{{ $lead_info->cl_twitter_account }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Facebook Id</label>
                                <input type="text" name="cl_facebook_id" id="CL_FACEBOOK_ID" class="form-control" maxlength="50" value="{{ $lead_info->cl_facebook_id }}" />
                            </div>
                        </div>
                        <div class="col-md-8">
                             <div class="form-group">
                                <label class="control-label">Need Shipment</label>
                                <ul class="RadioShip">
                                	<li>
                                		<label><input type="radio" {{ $lead_info->cl_need_shipment == "1" ? "checked" : "" }} name="need_shipment" id="CL_NEED_SHIPMENT_Y" value="1" /> Yes</label>
                                	</li>
                                	<li>
                                		<label><input type="radio" {{ $lead_info->cl_need_shipment == "0" ? "checked" : "" }} name="need_shipment" id="CL_NEED_SHIPMENT_N" value="0" /> No</label>
                                	</li>
                                </ul>
                            </div>
                        </div>
                   </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                    	<div class="col-md-12" align="left">
                		<h3 class="m--font-primary">Lead Image </h3>
                        	</div>
                             <div class="col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="AVATAR_PIC" width="100" src="{{ $img_src }}" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
        
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="clearfix margin-top-10">
                                    <div>
                                        <span class="btn default btn-file" style="text-align: left;">
                                            <span class="fileinput-new"> Select image </span><br/>
                                            <input type="file" name="cl_avatar_pic" id="CL_AVATAR_PIC" /> </span>
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
                			<h3 class="m--font-primary">Lead Address </h3>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Lead Country</label>
                                <select class="bs-select form-control" name="cl_country_id" id="CL_COUNTRY_ID" data-actions-box="true">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($lst_countries as $c_index => $c_info )
                                                <option {{ $c_info->id == $lead_info->cl_country_id ? "selected" : "" }} value="{{ $c_info->id }}">{{ $c_info->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">City</label>
                                <input type="text" name="cl_city" id="CL_CITY" class="form-control" maxlength="255" value="{{ $lead_info->cl_city }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">State</label>
                                <input type="text" name="cl_state" id="CL_STATE" class="form-control" maxlength="255" value="{{ $lead_info->cl_state }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Zip Code</label>
                                <input type="text" name="cl_zip_code" id="CL_ZIP_CODE" class="form-control" maxlength="5" value="{{ $lead_info->cl_zip_code }}" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Street Name</label>
                                <input type="text" name="cl_street_name" id="CL_STREET_NAME" class="form-control" maxlength="255" value="{{ $lead_info->cl_street_name }}" />
                            </div>
                        </div>
                     </div>
                    <div class="row" style="height:50px;"></div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                			<label>Lead Description </label>
                        </div>
                    </div>
                     <div class="row">
                    	<div class="col-md-12" align="left">
                		 	<textarea class="form-control" id="CL_LEAD_DESCRIPTION" name="cl_lead_description" style="width:100%;height:250px;resize:none" >{{ $lead_info->cl_lead_description }}</textarea>
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                      <div class="row">
                    	<div class="col-md-12" align="left">
                		 	@include('Leads.lead-management')
                        </div>
                    </div>
                    <div class="row" style="height:50px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_lead" id="BTN_SAVE_LEAD"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
<!--begin:: Upload Files Modal-->
<div class="modal fade" id="UploadFiles" tabindex="-1" role="dialog" aria-labelledby="UploadFilesModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="UploadFilesModalLabel">
					Upload Lead Files
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_lead_dropzone" id="FRM_LEAD_DROPZONE" class="dropzone" action="{{ url('request/upload/lead_files') }}" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				    <input type="hidden" name="lead_id" id="LEAD_ID" value="{{ $lead_info->cl_id }}" />
				 	<div class="row">
				 		<div class="col-md-12">
				 			<label>File Upload</label>
				 			<input type="file" id="LEAD_FILE" name="lead_file" />
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
				<button type="button" name="btn_upload_file" id="BTN_UPLOAD_FILE" class="btn btn-primary">
					Upload File
				</button>
			</div>
		</div>
	</div>
</div>
<!--end:: Upload Files Modal-->
<!--begin:: Insert Items Modal-->
<div class="modal fade" id="InserItems" tabindex="-1" role="dialog" aria-labelledby="InserItemsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					Insert Items
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_lead_insert_items" id="FRM_INSERT_ITEMS" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				    <input type="hidden" name="lead_id" id="LEAD_ID" value="{{ $lead_info->cl_id }}" />
				 	<div class="row">
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Service Category</label><br/>
                                     <select class="bs-select form-control" required="required" name="cs_service_category" id="CS_SERVICE_CATEGORY"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Service Category -- </option>
                                            @foreach($lst_service_categories as $key => $sc_info)
                                                    <option value="{{ $sc_info->sc_id }}">{{ $sc_info->sc_category_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Service</label><br/>
                                    <div class="ServicesDropdown" style="width:100%">
                                    	<select class="bs-select form-control" required="required" name="cs_services" id="CS_SERVICES"  style="width:100%" data-actions-box="true">
                                                <option value=""> -- Services-- </option>
                                        </select>
                                    </div>
                                </div>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
				<button type="button" name="btn_insert_items" id="BTN_INSERT_ITEMS" class="btn btn-primary">
					Insert Items
				</button>
			</div>
		</div>
	</div>
</div>
<!--end:: Insert Items Modal-->
@endsection