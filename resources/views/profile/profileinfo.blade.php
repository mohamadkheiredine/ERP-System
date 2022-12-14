<?php
/***********************************************************
profileinfo.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 2, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Profile Info
***********************************************************/

?>
<div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
	<div class="m-portlet__head">
		<div class="m-portlet__head-tools">
			<ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
				<li class="nav-item m-tabs__item">
					<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab" role="tab">
						<i class="flaticon-share m--hide"></i>
						Personal Info
					</a>
				</li>
				<li class="nav-item m-tabs__item">
					<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_profile_picture_tab" role="tab">
						Profile Picture
					</a>
				</li>
				<li class="nav-item m-tabs__item">
					<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_password_tab" role="tab">
						Password
					</a>
				</li>
			</ul>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item m-portlet__nav-item--last">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-gear"></i>
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
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Create Post
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Send Messages
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Upload File
													</span>
												</a>
											</li>
											<li class="m-nav__section">
												<span class="m-nav__section-text">
													Useful Links
												</span>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-info"></i>
													<span class="m-nav__link-text">
														FAQ
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-lifebuoy"></i>
													<span class="m-nav__link-text">
														Support
													</span>
												</a>
											</li>
											<li class="m-nav__separator m-nav__separator--fit m--hide"></li>
											<li class="m-nav__item m--hide">
												<a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
													Submit
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
	<div class="tab-content">
		<div class="tab-pane active" id="m_user_profile_tab">
			<form name="form_my_profile" id="FORM_MY_PROFILE" class="m-form m-form--fit m-form--label-align-right">
				<div class="m-portlet__body">
					 <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Full Name
						</label>
						<div class="col-7">
							<input class="form-control m-input" type="text" maxlength="500" name="u_fullname" id="U_FULLNAME"  value="{{ $user_info->u_fullname }}"  />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label" style="padding:0px;">
							Gender
						</label>
						<div class="col-7">
						     <div class="mt-radio-list" data-error-container="#GenderError">
                                <div class="row">
                                    <div class="col-md-6">
                                     <input type="radio" {{ $user_info->u_gender == "m" ? "checked='checked'" : "" }} name="u_gender"  value="m" /> Male
                                    </div>
                                    <div class="col-md-6">
                                    <input type="radio" {{ $user_info->u_gender == "f" ? "checked='checked'" : "" }}  name="u_gender"  value="f" /> Female
                                    </div>
                                </div>
                            </div>
                            <div id="GenderError"></div>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label" style="padding:0px;">
							Residential Area
						</label>
						<div class="col-7">
							<textarea style="width:100%;height: 100px;resize:none" class="form-control m-input" name="u_residential_area" id="U_RESIDENTIAL_AREA">{{ $user_info->u_residential_area }}</textarea>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Email <span class="required"> * </span>
						</label>
						<div class="col-7">
							<input type="email" maxlength="255" name="u_email" id="U_EMAIL" class="form-control m-input" value="{{ $user_info->u_email }}" />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Date Of Birth <span class="required"> * </span>
						</label>
						<div class="col-7">
							<input type="text" name='u_date_birth' class="form-control m-input" id="U_DATE_BIRTH" value="{{ $user_info->u_date_birth }}" />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Mobile
						</label>
						<div class="col-7">
							<input type="text" maxlength="20" name="u_mobile" id="U_MOBILE" class="form-control m-input" value="{{ $user_info->u_mobile }}" />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Phone
						</label>
						<div class="col-7">
							<input type="text" maxlength="20" name="u_phone" id="U_PHONE" class="form-control m-input" value="{{ $user_info->u_phone }}" />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Fax
						</label>
						<div class="col-7">
							<input type="text" maxlength="20" name="u_fax" id="U_FAX" class="form-control m-input" value="{{ $user_info->u_fax }}" />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Website
						</label>
						<div class="col-7">
                            <input type="text" maxlength="255" name="u_website" id="U_WEBSITE" class="form-control m-input" value="{{ $user_info->u_website }}" />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Job Role
						</label>
						<div class="col-7">
                            <select name="u_job_role_id" id="U_JOB_ROLE_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_job_roles as $key => $jr_info)
                                 <option {{ $user_info->u_job_role_id == $jr_info->jr_id ? "selected" : "" }} value="{{ $jr_info->jr_id }}">{{ $jr_info->jr_job_role }}</option>
                                @endforeach 
                            </select>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Job Title
						</label>
						<div class="col-7">
                           <select name="u_job_title_id" id="U_JOB_TITLE_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_job_titles as $key => $jt_info)
                                 <option {{ $user_info->u_job_title_id == $jt_info->jt_id ? "selected" : "" }} value="{{ $jt_info->jt_id }}">{{ $jt_info->jt_job_title }}</option>
                                @endforeach 
                            </select>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Department
						</label>
						<div class="col-7">
                          	<select name="u_department_id" id="U_DEPARTMENT_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_departments as $key => $dep_info)
                                 <option {{ $user_info->u_department_id == $dep_info->sd_id ? "selected" : "" }} value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                @endforeach 
                            </select>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Language
						</label>
						<div class="col-7">
                          	<select name="u_lang_id" id="U_LANG_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_langs as $key => $lang_info)
                                 <option {{ $user_info->u_lang_id == $lang_info->lm_id ? "selected" : "" }} value="{{ $lang_info->lm_id }}">{{ $lang_info->lm_lang_name }}</option>
                                @endforeach 
                            </select>
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Address
						</label>
						<div class="col-7">
                          	<textarea style="width:100%;height: 100px;resize:none" class="form-control m-input" name="u_address" id="U_ADDRESS">{{ $user_info->u_address }}</textarea>
						</div>
					</div>
				</div>
				<div class="m-portlet__foot m-portlet__foot--fit">
					<div class="m-form__actions">
						<div class="row">
							<div class="col-2"></div>
							<div class="col-7">
								<button type="button" name="btn_save_info" id="BTN_SAVE_INFO" class="btn btn-accent m-btn m-btn--air m-btn--custom">
									Save changes
								</button>
								&nbsp;&nbsp;
								<button type="reset" class="btn btn-secondary m-btn m-btn--air m-btn--custom">
									Cancel
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="tab-pane " id="m_profile_picture_tab">
			<div class="row">
				<div class="col-md-12" style="height:10px;"></div>
			</div>
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
                    <form action="#" name="frm_upload_image" id="FRM_UPLOAD_IMAGE" role="form"  enctype="multipart/form-data" >
                    	 <span id="hidden_fields">
                            {!! csrf_field() !!}
                        </span>
                        <div class="form-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 150px; height: auto;">
                                    <img id="IMAGE_PROFILE" style="height: 200px;width:auto" src="{{ Session('user_profile_url') }}" alt="" /> </div>
                            </div>
                            
                            <div class="clearfix margin-top-10">
                            	                                <div   style="max-width: 200px; max-height:150px;clear: both;">&nbsp;</div> 
                                <div class="row">
                                	<div class="col-md-12">
                                		<input type="file" name="u_profile_pic" id="U_PROFILE_PIC"  /> 
                                	</div>
                                	<div class="col-md-12">
                                		    <span class="label label-danger">NOTE! </span>
                                			<span>Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                	</div>
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-10">
                            	
                            </div>
                            <div class="col-md-2" align="right">
                            	<button type="button" name="btn_upload_image" id="BTN_UPLOAD_IMAGE" class="btn btn-primary" >Upload Image </button>
                            </div>
                        </div>
                        
                    </form>
				</div>
				<div class="col-md-1"></div>
			</div>
		</div>
		<div class="tab-pane " id="m_user_password_tab">
			<form name="form_profile_password" id="FORM_PROFILE_PASSWORD" class="m-form m-form--fit m-form--label-align-right">
				<div class="m-portlet__body">
					 <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Old Password
						</label>
						<div class="col-7">
							<input class="form-control m-input" type="password" maxlength="500" name="uo_old_password" id="UO_OLD_PASSWORD"  value=""  />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							New Password
						</label>
						<div class="col-7">
							<input class="form-control m-input" type="password" maxlength="500" name="un_new_password" id="UN_NEW_PASSWORD"  value=""  />
						</div>
					</div>
					<div class="form-group m-form__group row">
						<label for="example-text-input" class="col-2 col-form-label">
							Confirm Password
						</label>
						<div class="col-7">
							<input class="form-control m-input" type="password" maxlength="500" name="un_confirm_password" id="UN_CONFIRM_PASSWORD"  value=""  />
						</div>
					</div>
				</div>
				<div class="m-portlet__foot m-portlet__foot--fit">
					<div class="m-form__actions">
						<div class="row">
							<div class="col-2"></div>
							<div class="col-7">
								<button type="submit" name="btn_change_password" id="BTN_CHANGE_PASSWORD" class="btn btn-accent m-btn m-btn--air m-btn--custom">
									Change Password
								</button>
								&nbsp;&nbsp;
								<button type="reset" class="btn btn-secondary m-btn m-btn--air m-btn--custom">
									Cancel
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>