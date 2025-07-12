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
	<link rel="stylesheet" type="text/css" href="{{ url('default/assets/plugins/jquery-comments/css/jquery-comments.css') }}" />
	<link rel="stylesheet" href="{{ url('default/assets/plugins/scheduler/codebase/dhtmlxscheduler_material.css?v=5.2.2') }}" type="text/css" charset="utf-8" />

        <style>
        .form-control{
            color:#0000FF !important;
        }
        </style>
@endsection
@section('plugins')
	<script src="{{ url('default/assets/plugins/scheduler/codebase/dhtmlxscheduler.js?v=5.2.2') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('default/assets/plugins/jquery-comments/js/jquery-comments.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/contacts.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/crm/savelead.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">{{ "Manage Lead " .  $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</h3>
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
    <form name="frm_save_lead" id="FORM_SAVE_LEAD">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                      <input type="hidden" name="cl_date_creation" value="{{ $lead_info->cl_date_creation }}" />
                        <input type="hidden" name="cl_type_items" id="CL_TYPE_ITEMS" value="1" />
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
                                <label class="control-label">sheet number <span class="required"> * </span></label>
                                <input type="text" name="cl_sheet_number" id="CL_SHEET_NUMBER" class="form-control" required="required" maxlength="15" placeholder="sheet number"  value="{{ $lead_info->cl_sheet_number }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Full Name <span class="required"> * </span></label>
                                <input type="text" name="cl_full_name" id="CL_FULL_NAME" class="form-control" required="required" maxlength="255" placeholder="Full Name"  value="{{ $lead_info->cl_first_name }}&nbsp;{{ $lead_info->cl_last_name }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Mobile <span class="required"> * </span></label>
                                <input type="text" name="cl_mobile" id="CL_MOBILE" required="required" class="form-control" maxlength="25"  value="{{ $lead_info->cl_mobile }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Region <span class="required"> * </span> </label>
                                <input type="text" name="cl_region"  required="required"  id="CL_REGION" class="form-control" maxlength="255" value="{{ $lead_info->cl_region }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Area <span class="required"> * </span> </label>
                                <select name="cl_area" required="required" id="CL_AREA"  tabindex="5"  class="form-control form-select" data-control="select2" data-placeholder="Select Area">
                                    <option value="">-- Select Area --</option>
                                    <?php foreach ( $lst_areas as $key => $area_info ) { ?>
                                    <option {{ $lead_info->cl_area == $area_info->la_area ? "selected" : "" }} value="<?php echo $area_info->la_area;  ?>"><?php echo $area_info->la_area;  ?></option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Salesman <span class="required"> * </span> </label>
                                <select name="cl_sales_id" required="required" id="CL_SALES_ID"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
                                        <option value="">-- Select User --</option>
                                        <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                                <option {{ $lead_info->cl_sales_id == $user_info->id ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>Telemarketing <span class="required"> * </span>  </label>
                                    <select name="cl_telemarketing_id" id="CL_TELEMARKETING_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                        <option value="">-- Select Telemarketing --</option>
                                        <?php foreach ( $lst_telemarketing as $key => $user_info ) { ?>
                                                <option {{ $lead_info->cl_telemarketing_id == $user_info->id ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                        <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Lead Type <span class="required"> * </span> </label>
                                <select name="cl_lead_type_id" id="CL_LEAD_TYPE_ID"  required="required" class="form-control form-select" data-control="select2" data-placeholder="Select Lead Type">
                                        <option value="">-- Select Lead Type --</option>
                                        @foreach ($lst_lead_types as $ind_index => $type_info )
                                                <option {{ $lead_info->cl_lead_type_id == $type_info->lt_id ? "selected" : "" }} value="{{ $type_info->lt_id }}">{{ $type_info->lt_deal_type }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Referred by<span class="required"> * </span></label>
                                <input type="text" name="cl_referred_by" required="required" class="form-control" value="{{ $lead_info->cl_referred_by }}" />
                            </div>
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
 <div class="modal fade" tabindex="-1" id="UploadFiles">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload Lead Files</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
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


 <div class="modal fade" tabindex="-1" id="InserItems">
    <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
                <h3 class="modal-title">Insert items</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
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
@endsection
