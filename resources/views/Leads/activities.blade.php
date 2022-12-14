<?php
/***********************************************************
activities.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Feb 14, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
page of management for Activities of sales in this system
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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/activities.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/activities.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Activities Management
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
												<a data-action_type="PRINT"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-print"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="EXPORT_AS_CSV"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-download"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a data-action_type="IMPORT"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon fa fa-upload"></i>
													<span class="m-nav__link-text">
														Import
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a  data-action_type="DOWNLOAD_TEMPLATE"  href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-download"></i>
													<span class="m-nav__link-text">
														Download Import Template
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
	<span id="hidden_fields"> 
	</span>
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="ACTIVITIES_LEAD" name="activities_lead">
                            			<option value="0">-- Select lead --</option>
                                        @foreach($lst_leads as $index => $lead_info)
                                          <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						<div class="col-md-4">
							<select class="bs-select form-control" id="ACTIVITIES_USER" name="activities_user">
                    			<option value="0">--All Users--</option>
                                @foreach($lst_users as $index => $user_info)
                                  <option {{ $user_info->id == session("user_id") ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ url('crm/addactivity') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="flaticon-users"></i>
							<span>
								New Activity
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
		<div class="row">
			<div class="col-md-12" id="LstActivities">
			</div>			
		</div> 
	</div>
</div>
@endsection