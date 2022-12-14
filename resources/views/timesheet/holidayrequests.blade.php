<?php
/***********************************************************
holidayrequests.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 3, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Holiday Requests"])

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
<script type="text/javascript" src="{{ url('js/modules/companyholidays.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/holidayrequest.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Holiday Requests
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
											<li class="m-nav__item">
												<a data-action_type="PRINT"  href="" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="EXPORT_AS_CSV"  href="" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="IMPORT" href="" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Import
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" data-action_type="DOWNLOAD_IMPORT_TEMPLATE" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Download Import Template
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="#"   data-action_type="CHANGE_REQUEST_STATUS"  class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Change Request Status
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
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-12">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-3">
						<label class="control-label">&nbsp;</label>
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-3">
                           	<label class="control-label">Department</label>
                            <select name="hr_department_id" id="HR_DEPARTMENT_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_departments as $key => $dep_info)
                                 <option value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                @endforeach 
                            </select>
						</div>
						<div class="col-md-3">
                           	<label class="control-label">Users</label>
                            <select name="hr_user_id" id="HR_USER_ID" class="form-control m-select2" style="width:100%">
                                <option value="">--Select One--</option>
                                @foreach( $lst_users as $key => $user_info )
                                 <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach 
                            </select>
						</div>
					</div>
					<div class="col-md-3">
					<label class="control-label">Status</label>
                    <select name="hr_request_status" id="HR_REQUEST_STATUS" class="form-control m-select2" style="width:100%">
                        <option value="0">Pending</option>
                        <option value="1">Allow</option>
                        <option value="2">Denied</option>
                    </select>
				</div>
				</div>
				
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="m_datatable" id="LstHolidayRequests">

		</div>
		<!--end: Datatable -->
		<div class="modal fade" id="ApproveDenyModel" tabindex="-1" role="dialog" aria-labelledby="ApproveDenyModelLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Request Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  		<form name="frm_change_status" id="FRM_CHANGE_STATUS">
                  				<input type="hidden" name="tr_request_ids" value="" />
                  			    {!! csrf_field() !!}
                          	<div class="row">
                          		<div class="col-md-12">
                          			<div class="form-group">
                          				<label class="control-label">Status</label>
                                        <select name="request_status" id="REQUEST_STATUS" class="form-control m-select2" style="width:100%">
                                            <option value="0">Pending</option>
                                            <option value="1">Allow</option>
                                            <option value="-1">Denied</option>
                                        </select>
                          			</div>
                          		</div>
                          		<div class="col-md-12">
                          			<div class="form-group">
                          				<label class="control-label">Information</label>
                                        <textarea rows="" style="width:100%;height:150px;" id="REQUEST_INFORMATION" name="request_information"></textarea>
                          			</div>
                          		</div>
                          	</div>
                          	 	</form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" name="btn_change_status" id="BTN_CHANGE_STATUS" class="btn btn-primary">Change Status</button>
                          </div>
                 
                </div>
              </div>
            </div>
	</div>
</div>
@endsection