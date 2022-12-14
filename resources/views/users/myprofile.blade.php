<?php
/***********************************************************
myprofile.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Products Management"])

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
<script type="text/javascript" src="{{ url('js/modules/myprofile.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/users/myprofile.js') }}"></script>
@endsection

@section('content')
<div class="row">
		<div class="col-xl-3 col-lg-4">
			<div class="m-portlet m-portlet--full-height">
				<div class="m-portlet__body">
					<div class="m-card-profile">
						<div class="m-card-profile__title m--hide">
							Your Profile
						</div>
						<div class="m-card-profile__pic">
							<div class="m-card-profile__pic-wrapper">
								<img src="{{ Session('user_profile_url') }}" alt=""/>
							</div>
						</div>
						<div class="m-card-profile__details">
							<span class="m-card-profile__name">
								{{ Session('user_fullname') }}
							</span>
							<a href="" class="m-card-profile__email m-link">
								{{ Session('user_email') }}
							</a>
						</div>
					</div>
					<ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
						<li class="m-nav__separator m-nav__separator--fit"></li>
						<li class="m-nav__section m--hide">
							<span class="m-nav__section-text">
								Section
							</span>
						</li>
						<li class="m-nav__item">
							<a href="#" id="MY_PROFILE" class="m-nav__link">
								<i class="m-nav__link-icon flaticon-profile-1"></i>
								<span class="m-nav__link-title">
									<span class="m-nav__link-wrap">
										<span class="m-nav__link-text">
											My Profile
										</span> 
									</span>
								</span>
							</a>
						</li>
						<li class="m-nav__item">
							<a href="#" id="LEADS" class="m-nav__link">
								<i class="m-nav__link-icon flaticon-share"></i>
								<span class="m-nav__link-text">
									Leads
								</span>
							</a>
						</li>
						<li class="m-nav__item">
							<a  href="#" id="timesheet"  class="m-nav__link">
								<i class="m-nav__link-icon flaticon-chat-1"></i>
								<span class="m-nav__link-text">
									Timesheet
								</span>
							</a>
						</li>
						<li class="m-nav__item">
							<a href="#" id="HOLIDAYS_CALENDAR" class="m-nav__link">
								<i class="m-nav__link-icon flaticon-graphic-2"></i>
								<span class="m-nav__link-text">
									Holiday's Calendar
								</span>
							</a>
						</li>
					</ul>
					<div class="m-portlet__body-separator"></div>
				</div>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8" id="PROFILE_PAGE">
			
		</div>
	</div>
	@endsection