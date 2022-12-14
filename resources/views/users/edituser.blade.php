<?php
/***********************************************************
 edituser.blade.php
 Product :
 Version : 1.0
 Release : 1
 Date Created : Jun 30, 2019
 Developed By  : Mohamad Mantach   PHP Department itm Solutions
 All Rights Reserved ,   itm Solutions COPYRIGHT 2019
 
 Page Description :
 
 ***********************************************************/



{
    $image_src_url  = url('/')."/".Config::get('constants.USERS_PATH').$user_info->u_avatar_base_src.$user_info->u_avatar_filename.".".$user_info->u_avatar_extentions;
    $image_src_path = public_path(). "/" .Config::get('constants.USERS_PATH').$user_info->u_avatar_base_src.$user_info->u_avatar_filename.".".$user_info->u_avatar_extentions;
    if(strlen($user_info->u_avatar_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }
    
}

?>

@extends('layouts.layout',['page_title' => "Users Management"])

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
<script type="text/javascript" src="{{ url('js/modules/users.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/users/saveusers.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Edit Existing User</h3>
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
            <form name="form_save_users" id="FORM_SAVE_USERS">
            <div  class="form-body">
             <span id="hidden_fields">
                             <input type="hidden" name="user_id" value="{{ $user_info->id }}" />
                        {!! csrf_field() !!}
            </span>
                    <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> User Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
                <div class="row">
                	<div class="col-md-12">
                		<div class="form-group">
                            <div class="col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="PROFILE_PIC" height="120" src="{{ $img_src }}" alt="" /> </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
    
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="clearfix margin-top-10">
                                    <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <input type="file" name="u_profile_pic" id="U_PROFILE_PIC" /> </span>
                                    </div>
                                    <br>
                                    <span class="label label-danger"> NOTE! </span><br><br>
                                    <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                </div>
                            </div>
    
                        </div>
                	</div>
                	<div class="col-md-12">
                		<div class="m-portlet m-portlet--tab">
							<div class="m-portlet__head bg-success">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<span class="m-portlet__head-icon m--hide">
											<i class="la la-gear"></i>
										</span>
										<h3 class="m-portlet__head-text" style="color:white">
											User Information
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="row">
                                   <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Full Name <span class="required"> * </span></label>
                                            <input type="text" maxlength="500" name="u_fullname" id="U_FULLNAME" class="form-control" value="{{ $user_info->u_fullname }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Username<span class="required"> * </span></label>
                                            <input type="text" maxlength="255" name="u_username" id="U_USERNAME" class="form-control" value="{{ $user_info->u_username }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Access Code<span class="required"> * </span></label>
                                            <input type="text" maxlength="5" autocomplete="off" name="u_attendance_code" id="U_ATTENDANCE_CODE" class="form-control" value="{{ $user_info->u_attendance_code }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">password <span class="required"> * </span></label>
                                            <input type="password" name="u_password" maxlength="150" id="U_PASSWORD" class="form-control" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Re-type password <span class="required"> * </span></label>
                                            <input type="password" name="retype_u_password" maxlength="150" id="RETYPE_U_PASSWORD" class="form-control" value="" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> User Type <span class="required"> * </span></label>
                                            <select class="bs-select form-control" name="u_user_type" id="U_USER_TYPE" data-actions-box="true">
                                                    <option value="">No User Type</option>
                                                   <option {{ $user_info->u_user_type == 1 ? "selected" : "" }} value="1">Admin</option> 
                                                   <option {{ $user_info->u_user_type == 2 ? "selected" : "" }} value="2">Employee</option> 
                                                   <option {{ $user_info->u_user_type == 3 ? "selected" : "" }} value="3">Manager</option>
                                                   <option {{ $user_info->u_user_type == 4 ? "selected" : "" }} value="4">POS Users</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                         <div class="form-group">
                                            <label class="control-label">Role <span class="required"> * </span></label>
                                             <select name="u_role" id="U_ROLE" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach( $lst_roles as $key => $role_info)
                                                 <option {{ $user_info->fk_role_id  ==  $role_info->role_id  ? "selected" : "" }} value="{{ $role_info->role_id }}">{{ $role_info->role_name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                         <div class="form-group">
                                            <label class="control-label">Language <span class="required"> * </span></label>
                                             <select name="u_lang_id" id="U_LANG_ID" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach($lst_langs as $key => $lang_info)
                                                 <option {{ $user_info->u_lang_id ==  $lang_info->lm_id  ? "selected" : "" }} value="{{ $lang_info->lm_id }}">{{ $lang_info->lm_lang_name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                     <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label">Address</label>
                                            <textarea style="width:100%;height: 100px;resize:none" class="form-control" name="u_address" id="U_ADDRESS">{{ $user_info->u_address }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="height: 40px;">
                                    	<label class="col-12 col-form-label">
											Enable User
										</label>
										<div class="col-12">
											<span class="m-switch m-switch--icon m-switch--success">
												<label>
													<input type="checkbox" name="u_is_active" {{ $user_info->u_is_active == 1 ? "checked='checked'" : "" }}  value='1' />
													<span></span>
												</label>
											</span>
										</div> 
                                    </div> 
                                </div>
							</div>
						</div>
                	</div> 
                </div> 
               <div class="row" style="height:5px;"></div>
                <div class="row">
               		<div class="col-md-12">
               			<div class="m-portlet m-portlet--tab">
							<div class="m-portlet__head bg-info">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<span class="m-portlet__head-icon m--hide">
											<i class="la la-gear"></i>
										</span>
										<h3 class="m-portlet__head-text" style="color:white">
											Personal Information
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="row">
									<div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Gender</label><br/>
                                            <div class="mt-radio-list" data-error-container="#GenderError">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                         <input type="radio" {{ $user_info->u_gender = "m" ? "checked='checked'" : "" }} name="u_gender"  value="m" /> Male
                                                        </div>
                                                        <div class="col-md-6">
                                                        <input type="radio" {{ $user_info->u_gender = "f" ? "checked='checked'" : "" }}   name="u_gender"  value="f" /> Female
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="GenderError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Residential Area</label>
                                            <textarea style="width:100%;height: 100px;resize:none" class="form-control" name="u_residential_area" id="U_RESIDENTIAL_AREA">{{ $user_info->u_residential_area }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Email <span class="required"> * </span></label>
                                            <input type="email" maxlength="255" name="u_email" id="U_EMAIL" class="form-control" value="{{ $user_info->u_email }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                         <div class="form-group">
                                            <label class="control-label">Date Of Birth <span class="required"> * </span></label>
                                             <input type="text" name='u_date_birth' value="{{ $user_info->u_date_birth }}" class="form-control" id="U_DATE_BIRTH" />
                                        </div>
                                    </div><div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">mobile</label>
                                            <input type="text" maxlength="20" name="u_mobile" id="U_MOBILE" class="form-control" value="{{ $user_info->u_mobile }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Phone</label>
                                            <input type="text" maxlength="20" name="u_phone" id="U_PHONE" class="form-control" value="{{ $user_info->u_phone }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Fax</label>
                                            <input type="text" maxlength="20" name="u_fax" id="U_FAX" class="form-control" value="{{ $user_info->u_fax }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Website</label>
                                            <input type="text" name="u_website" id="U_WEBSITE" class="form-control" value="{{ $user_info->u_website }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Marital Status</label>
                                            <select class="bs-select form-control" name="u_marital_status" id="U_MARITAL_STATUS" data-actions-box="true">
                                                    <option value="">-- Select One --</option>
                                                   <option {{ $user_info->u_marital_status == 1 ? "selected" : "" }} value="1">single</option> 
                                                   <option {{ $user_info->u_marital_status == 2 ? "selected" : "" }} value="2">married</option> 
                                                   <option {{ $user_info->u_marital_status == 3 ? "selected" : "" }} value="3">widowed</option>
                                                   <option {{ $user_info->u_marital_status == 4 ? "selected" : "" }} value="4">divorced</option>
                                                   <option {{ $user_info->u_marital_status == 5 ? "selected" : "" }} value="5">separated</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">Number of Dependency</label>
                                             <input type="number" name='u_number_of_dependencies' step="1" min="0" class="form-control" id="U_NUMBER_OD_DEPENDENCIES" value="{{ $user_info->u_number_of_dependencies }}" />
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="row">
               		<div class="col-md-12">
               			<div class="m-portlet m-portlet--tab">
							<div class="m-portlet__head bg-primary">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<span class="m-portlet__head-icon m--hide">
											<i class="la la-gear"></i>
										</span>
										<h3 class="m-portlet__head-text" style="color:white">
											Employment Information
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="row">
									<div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Company</label>
                                            <select name="fk_company_id" id="FK_COMPANY_ID" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach( $lst_companies as $key => $cmp_info)
                                                 <option {{ $user_info->fk_company_id == $cmp_info->cd_id ? "selected" : "" }} value="{{ $cmp_info->cd_id }}">{{ $cmp_info->cd_company_name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Department</label>
                                            <select name="u_department_id" id="U_DEPARTMENT_ID" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach( $lst_departments as $key => $dep_info)
                                                 <option {{ $user_info->u_department_id == $dep_info->sd_id ? "selected" : "" }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div> 
									<div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Warehouse Responsible</label>
                                            <select name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach( $lst_warhouses as $key => $warehouse_info)
                                                 <option {{ $user_info->fk_warehouse_id == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Job Role</label>
                                            <select name="u_job_role_id" id="U_JOB_ROLE_ID" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach( $lst_job_roles as $key => $jr_info)
                                                 <option {{ $user_info->u_job_role_id == $jr_info->jr_id ? "selected" : "" }}  value="{{ $jr_info->jr_id }}">{{ $jr_info->jr_job_role }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Job Title</label>
                                            <select name="u_job_title_id" id="U_JOB_TITLE_ID" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                @foreach( $lst_job_titles as $key => $jt_info)
                                                 <option {{ $user_info->u_job_title_id == $jt_info->jt_id ? "selected" : "" }} value="{{ $jt_info->jt_id }}">{{ $jt_info->jt_job_title }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Employee Type</label>
                                            <select name="u_employee_type" id="U_EMPLOYEE_TYPE" class="form-control m-select2" style="width:100%">
                                                <option value="">--Select One--</option>
                                                    @foreach( $lst_employment_type as $key => $et_info)
                                                 <option {{ $user_info->u_employee_type == $et_info->et_id ? "selected" : "" }}  value="{{ $et_info->et_id }}">{{ $et_info->et_type }}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">Employment Date <span class="required"> * </span></label>
                                             <input type="text" name='u_employment_date' class="form-control" id="U_EMPLOYMENT_DATE" value="{{ $user_info->u_employment_date }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">Daily Working Hour </label>
                                             <input type="number" name='u_daily_working_hours' step="0.1" class="form-control" id="U_DAILY_WORKING_HOURS" value="{{ $user_info->u_daily_working_hours }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">Sales Commission</label>
                                             <input type="text" name='u_sales_commission' class="form-control" id="U_SALES_COMISSION" value="{{ $user_info->u_sales_commission }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">User Sallary</label>
                                             <input type="text" name='u_user_sallary' class="form-control" id="U_USER_SALLARY" value="{{ $user_info->u_user_sallary }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">Holidays</label>
                                             <input type="number" min="0" max="100" step="1"  name='u_number_holidays' class="form-control" id="U_NUMBER_HOLIDAYS" value="{{ $user_info->u_number_holidays }}" />
                                        </div>
                                    </div>
                                     <div class="col-md-4" style="height: 40px;">
                                    	<label class="col-12 col-form-label">
											Has Insurance
										</label>
										<div class="col-12">
											<span class="m-switch m-switch--icon m-switch--success">
												<label>
													<input type="checkbox" name="u_has_insurance"  {{ $user_info->u_has_insurance == 1 ? "checked='checked'" : "" }}  value='1' />
													<span></span>
												</label>
											</span>
										</div> 
                                    </div>
                                    <div class="col-md-4">
                                       <div class="form-group">
                                            <label class="control-label">CNSS Number</label>
                                             <input type="text" name='u_cnss_number' maxlength="20" class="form-control" id="U_USER_SALLARY" value="{{ $user_info->u_cnss_number }}" />
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
               
                <div class="row" style="height:10px;"></div>
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3" align="right">
                            <button type="submit" id="BTN_SAVE_USER" name="btn_save_user" class="btn btn-primary btn-wide">Save</button>&nbsp;&nbsp;<button type="button" id="BACK_FORM" name="back_form" class="btn btn-dark btn-wide">Back</button>
                    </div>
                </div>
            </div>
        </form>
	</div>
</div>
@endsection