<?php
/***********************************************************
maindashboard.blade.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Nov 3, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Dashboard"])

@section('plugins')
    <script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
    <script src="{{ url('admin/assets/pages/scripts/dashboard.js') }}" type="text/javascript"></script>
    <script src="{{ url('default/assets/app/js/service-dashboard.js') }}" type="text/javascript"></script> 
@endsection
@section('themes')
<link href="{{ url('admin/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('admin/assets/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    .amcharts-export-menu.amcharts-export-menu-top-right.amExportButton {
        display: none;
    }
</style>
@endsection

@section('content')
<!--begin::Row-->
	<div class="row g-5 g-xl-8">
		<!--begin::Col-->
		<div class="col-xl-4">
			<!--begin::Misc Widget 1-->
			<div class="row mb-5 mb-xl-8 g-5 g-xl-8">
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/account/overview.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-gift fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
							</i>
							<span class="fs-4 fw-bold">User Profile</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/account/statements.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-technology-2 fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<span class="fs-4 fw-bold">Statements</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/account/referrals.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-fingerprint-scanning fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
								<span class="path5"></span>
							</i>
							<span class="fs-4 fw-bold">Best Referrals</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/apps/customers/view.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-abstract-26 fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<span class="fs-4 fw-bold">Hot Picks</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/apps/projects/project.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-basket fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
							</i>
							<span class="fs-4 fw-bold">Latest Trands</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/apps/projects/users.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-rocket fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<span class="fs-4 fw-bold">New Arrivals</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Misc Widget 1-->
			<!--begin::List Widget 5-->
			<div class="card mb-5 mb-xl-8">
				<!--begin::Header-->
				<div class="card-header align-items-center border-0 mt-4">
					<h3 class="card-title align-items-start flex-column">
						<span class="fw-bold mb-2 text-dark">Activities</span>
						<span class="text-muted fw-semibold fs-7">890,344 Sales</span>
					</h3>
					<div class="card-toolbar">
						<!--begin::Menu-->
						<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
							<i class="ki-duotone ki-category fs-6">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
							</i>
						</button>
						<!--begin::Menu 1-->
						<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_64b785078d292">
							<!--begin::Header-->
							<div class="px-7 py-5">
								<div class="fs-5 text-dark fw-bold">Filter Options</div>
							</div>
							<!--end::Header-->
							<!--begin::Menu separator-->
							<div class="separator border-gray-200"></div>
							<!--end::Menu separator-->
							<!--begin::Form-->
							<div class="px-7 py-5">
								<!--begin::Input group-->
								<div class="mb-10">
									<!--begin::Label-->
									<label class="form-label fw-semibold">Status:</label>
									<!--end::Label-->
									<!--begin::Input-->
									<div>
										<select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_64b785078d292" data-allow-clear="true">
											<option></option>
											<option value="1">Approved</option>
											<option value="2">Pending</option>
											<option value="2">In Process</option>
											<option value="2">Rejected</option>
										</select>
									</div>
									<!--end::Input-->
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="mb-10">
									<!--begin::Label-->
									<label class="form-label fw-semibold">Member Type:</label>
									<!--end::Label-->
									<!--begin::Options-->
									<div class="d-flex">
										<!--begin::Options-->
										<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
											<input class="form-check-input" type="checkbox" value="1" />
											<span class="form-check-label">Author</span>
										</label>
										<!--end::Options-->
										<!--begin::Options-->
										<label class="form-check form-check-sm form-check-custom form-check-solid">
											<input class="form-check-input" type="checkbox" value="2" checked="checked" />
											<span class="form-check-label">Customer</span>
										</label>
										<!--end::Options-->
									</div>
									<!--end::Options-->
								</div>
								<!--end::Input group-->
								<!--begin::Input group-->
								<div class="mb-10">
									<!--begin::Label-->
									<label class="form-label fw-semibold">Notifications:</label>
									<!--end::Label-->
									<!--begin::Switch-->
									<div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
										<input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
										<label class="form-check-label">Enabled</label>
									</div>
									<!--end::Switch-->
								</div>
								<!--end::Input group-->
								<!--begin::Actions-->
								<div class="d-flex justify-content-end">
									<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
									<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
								</div>
								<!--end::Actions-->
							</div>
							<!--end::Form-->
						</div>
						<!--end::Menu 1-->
						<!--end::Menu-->
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-5">
					<!--begin::Timeline-->
					<div class="timeline-label">
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">08:42</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-warning fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Text-->
							<div class="fw-mormal timeline-content text-muted ps-3">Outlines keep you honest. And keep structure</div>
							<!--end::Text-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">10:00</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-success fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Content-->
							<div class="timeline-content d-flex">
								<span class="fw-bold text-gray-800 ps-3">AEOL meeting</span>
							</div>
							<!--end::Content-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">14:37</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-danger fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Desc-->
							<div class="timeline-content fw-bold text-gray-800 ps-3">Make deposit
							<a href="#" class="text-primary">USD 700</a>. to ESL</div>
							<!--end::Desc-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">16:50</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-primary fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Text-->
							<div class="timeline-content fw-mormal text-muted ps-3">Indulging in poorly driving and keep structure keep great</div>
							<!--end::Text-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">21:03</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-danger fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Desc-->
							<div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
							<a href="#" class="text-primary">#XF-2356</a>.</div>
							<!--end::Desc-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">16:50</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-primary fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Text-->
							<div class="timeline-content fw-mormal text-muted ps-3">Indulging in poorly driving and keep structure keep great</div>
							<!--end::Text-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">21:03</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-danger fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Desc-->
							<div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
							<a href="#" class="text-primary">#XF-2356</a>.</div>
							<!--end::Desc-->
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="timeline-item">
							<!--begin::Label-->
							<div class="timeline-label fw-bold text-gray-800 fs-6">10:30</div>
							<!--end::Label-->
							<!--begin::Badge-->
							<div class="timeline-badge">
								<i class="fa fa-genderless text-success fs-1"></i>
							</div>
							<!--end::Badge-->
							<!--begin::Text-->
							<div class="timeline-content fw-mormal text-muted ps-3">Finance KPI Mobile app launch preparion meeting</div>
							<!--end::Text-->
						</div>
						<!--end::Item-->
					</div>
					<!--end::Timeline-->
				</div>
				<!--end: Card Body-->
			</div>
			<!--end: List Widget 5-->
			<!--begin::List Widget 4-->
			<div class="card mb-xl-8">
				<!--begin::Header-->
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold text-dark">Trends</span>
						<span class="text-muted mt-1 fw-semibold fs-7">Latest tech trends</span>
					</h3>
					<div class="card-toolbar">
						<!--begin::Menu-->
						<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
							<i class="ki-duotone ki-category fs-6">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
							</i>
						</button>
						<!--begin::Menu 3-->
						<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
							<!--begin::Heading-->
							<div class="menu-item px-3">
								<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
							</div>
							<!--end::Heading-->
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3">Create Invoice</a>
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link flex-stack px-3">Create Payment
								<span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
									<i class="ki-duotone ki-information fs-6">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span></a>
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3">Generate Bill</a>
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
								<a href="#" class="menu-link px-3">
									<span class="menu-title">Subscription</span>
									<span class="menu-arrow"></span>
								</a>
								<!--begin::Menu sub-->
								<div class="menu-sub menu-sub-dropdown w-175px py-4">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Plans</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Billing</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Statements</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content px-3">
											<!--begin::Switch-->
											<label class="form-check form-switch form-check-custom form-check-solid">
												<!--begin::Input-->
												<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
												<!--end::Input-->
												<!--end::Label-->
												<span class="form-check-label text-muted fs-6">Recuring</span>
												<!--end::Label-->
											</label>
											<!--end::Switch-->
										</div>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu sub-->
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3 my-1">
								<a href="#" class="menu-link px-3">Settings</a>
							</div>
							<!--end::Menu item-->
						</div>
						<!--end::Menu 3-->
						<!--end::Menu-->
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-5">
					<!--begin::Item-->
					<div class="d-flex align-items-sm-center mb-7">
						<!--begin::Symbol-->
						<div class="symbol symbol-50px me-5">
							<span class="symbol-label">
								<img src="assets/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center" alt="" />
							</span>
						</div>
						<!--end::Symbol-->
						<!--begin::Section-->
						<div class="d-flex align-items-center flex-row-fluid flex-wrap">
							<div class="flex-grow-1 me-2">
								<a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Top Authors</a>
								<span class="text-muted fw-semibold d-block fs-7">Mark, Rowling, Esther</span>
							</div>
							<span class="badge badge-light fw-bold my-2">+82$</span>
						</div>
						<!--end::Section-->
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-sm-center mb-7">
						<!--begin::Symbol-->
						<div class="symbol symbol-50px me-5">
							<span class="symbol-label">
								<img src="assets/media/svg/brand-logos/telegram.svg" class="h-50 align-self-center" alt="" />
							</span>
						</div>
						<!--end::Symbol-->
						<!--begin::Section-->
						<div class="d-flex align-items-center flex-row-fluid flex-wrap">
							<div class="flex-grow-1 me-2">
								<a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Popular Authors</a>
								<span class="text-muted fw-semibold d-block fs-7">Randy, Steve, Mike</span>
							</div>
							<span class="badge badge-light fw-bold my-2">+280$</span>
						</div>
						<!--end::Section-->
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-sm-center mb-7">
						<!--begin::Symbol-->
						<div class="symbol symbol-50px me-5">
							<span class="symbol-label">
								<img src="assets/media/svg/brand-logos/vimeo.svg" class="h-50 align-self-center" alt="" />
							</span>
						</div>
						<!--end::Symbol-->
						<!--begin::Section-->
						<div class="d-flex align-items-center flex-row-fluid flex-wrap">
							<div class="flex-grow-1 me-2">
								<a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">New Users</a>
								<span class="text-muted fw-semibold d-block fs-7">John, Pat, Jimmy</span>
							</div>
							<span class="badge badge-light fw-bold my-2">+4500$</span>
						</div>
						<!--end::Section-->
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-sm-center mb-7">
						<!--begin::Symbol-->
						<div class="symbol symbol-50px me-5">
							<span class="symbol-label">
								<img src="assets/media/svg/brand-logos/bebo.svg" class="h-50 align-self-center" alt="" />
							</span>
						</div>
						<!--end::Symbol-->
						<!--begin::Section-->
						<div class="d-flex align-items-center flex-row-fluid flex-wrap">
							<div class="flex-grow-1 me-2">
								<a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Active Customers</a>
								<span class="text-muted fw-semibold d-block fs-7">Mark, Rowling, Esther</span>
							</div>
							<span class="badge badge-light fw-bold my-2">+686$</span>
						</div>
						<!--end::Section-->
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-sm-center mb-7">
						<!--begin::Symbol-->
						<div class="symbol symbol-50px me-5">
							<span class="symbol-label">
								<img src="assets/media/svg/brand-logos/kickstarter.svg" class="h-50 align-self-center" alt="" />
							</span>
						</div>
						<!--end::Symbol-->
						<!--begin::Section-->
						<div class="d-flex align-items-center flex-row-fluid flex-wrap">
							<div class="flex-grow-1 me-2">
								<a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Bestseller Theme</a>
								<span class="text-muted fw-semibold d-block fs-7">Disco, Retro, Sports</span>
							</div>
							<span class="badge badge-light fw-bold my-2">+726$</span>
						</div>
						<!--end::Section-->
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-sm-center">
						<!--begin::Symbol-->
						<div class="symbol symbol-50px me-5">
							<span class="symbol-label">
								<img src="assets/media/svg/brand-logos/fox-hub.svg" class="h-50 align-self-center" alt="" />
							</span>
						</div>
						<!--end::Symbol-->
						<!--begin::Section-->
						<div class="d-flex align-items-center flex-row-fluid flex-wrap">
							<div class="flex-grow-1 me-2">
								<a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Fox Broker App</a>
								<span class="text-muted fw-semibold d-block fs-7">Finance, Corporate, Apps</span>
							</div>
							<span class="badge badge-light fw-bold my-2">+145$</span>
						</div>
						<!--end::Section-->
					</div>
					<!--end::Item-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::List Widget 4-->
		</div>
		<!--end::Col-->
		<!--begin::Col-->
		<div class="col-xl-8 ps-xl-12">
			<!--begin::Engage widget 1-->
			<div class="card bgi-position-y-bottom bgi-position-x-end bgi-no-repeat bgi-size-cover min-h-250px bg-primary mb-5 mb-xl-8" style="background-position: 100% 50px;background-size: 500px auto;background-image:url('assets/media/misc/city.png')" dir="ltr">
				<!--begin::Body-->
				<div class="card-body d-flex flex-column justify-content-center ps-lg-12">
					<!--begin::Title-->
					<h3 class="text-white fs-2qx fw-bold mb-7">We are working
					<br />to boost lovely mood</h3>
					<!--end::Title-->
					<!--begin::Action-->
					<div class="m-0">
						<a href='#' class="btn btn-success fw-semibold px-6 py-3" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create a Store</a>
					</div>
					<!--begin::Action-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Engage widget 1-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-8">
				<!--begin::Col-->
				<div class="col-xl-6">
					<!--begin::Mixed Widget 5-->
					<div class="card card-xl-stretch mb-xl-8">
						<!--begin::Beader-->
						<div class="card-header border-0 py-5">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold fs-3 mb-1">Trends</span>
								<span class="text-muted fw-semibold fs-7">Latest trends</span>
							</h3>
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
									<i class="ki-duotone ki-category fs-6">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</button>
								<!--begin::Menu 3-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
									<!--begin::Heading-->
									<div class="menu-item px-3">
										<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
									</div>
									<!--end::Heading-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Create Invoice</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link flex-stack px-3">Create Payment
										<span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
											<i class="ki-duotone ki-information fs-6">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
											</i>
										</span></a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Generate Bill</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
										<a href="#" class="menu-link px-3">
											<span class="menu-title">Subscription</span>
											<span class="menu-arrow"></span>
										</a>
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Plans</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Billing</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Statements</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content px-3">
													<!--begin::Switch-->
													<label class="form-check form-switch form-check-custom form-check-solid">
														<!--begin::Input-->
														<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
														<!--end::Input-->
														<!--end::Label-->
														<span class="form-check-label text-muted fs-6">Recuring</span>
														<!--end::Label-->
													</label>
													<!--end::Switch-->
												</div>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3 my-1">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 3-->
								<!--end::Menu-->
							</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body d-flex flex-column">
							<!--begin::Chart-->
							<div class="mixed-widget-5-chart card-rounded-top" data-kt-chart-color="primary" style="height: 150px"></div>
							<!--end::Chart-->
							<!--begin::Items-->
							<div class="mt-5">
								<!--begin::Item-->
								<div class="d-flex flex-stack mb-5">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-2">
										<!--begin::Symbol-->
										<div class="symbol symbol-50px me-3">
											<div class="symbol-label bg-light">
												<img src="assets/media/svg/brand-logos/plurk.svg" class="h-50" alt="" />
											</div>
										</div>
										<!--end::Symbol-->
										<!--begin::Title-->
										<div>
											<a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Top Authors</a>
											<div class="fs-7 text-muted fw-semibold mt-1">Ricky Hunt, Sandra Trepp</div>
										</div>
										<!--end::Title-->
									</div>
									<!--end::Section-->
									<!--begin::Label-->
									<div class="badge badge-light fw-semibold py-4 px-3">+82$</div>
									<!--end::Label-->
								</div>
								<!--end::Item-->
								<!--begin::Item-->
								<div class="d-flex flex-stack mb-5">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-2">
										<!--begin::Symbol-->
										<div class="symbol symbol-50px me-3">
											<div class="symbol-label bg-light">
												<img src="assets/media/svg/brand-logos/figma-1.svg" class="h-50" alt="" />
											</div>
										</div>
										<!--end::Symbol-->
										<!--begin::Title-->
										<div>
											<a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Top Sales</a>
											<div class="fs-7 text-muted fw-semibold mt-1">PitStop Emails</div>
										</div>
										<!--end::Title-->
									</div>
									<!--end::Section-->
									<!--begin::Label-->
									<div class="badge badge-light fw-semibold py-4 px-3">+82$</div>
									<!--end::Label-->
								</div>
								<!--end::Item-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-2">
										<!--begin::Symbol-->
										<div class="symbol symbol-50px me-3">
											<div class="symbol-label bg-light">
												<img src="assets/media/svg/brand-logos/vimeo.svg" class="h-50" alt="" />
											</div>
										</div>
										<!--end::Symbol-->
										<!--begin::Title-->
										<div class="py-1">
											<a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Top Engagement</a>
											<div class="fs-7 text-muted fw-semibold mt-1">KT.com</div>
										</div>
										<!--end::Title-->
									</div>
									<!--end::Section-->
									<!--begin::Label-->
									<div class="badge badge-light fw-semibold py-4 px-3">+82$</div>
									<!--end::Label-->
								</div>
								<!--end::Item-->
							</div>
							<!--end::Items-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Mixed Widget 5-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-6">
					<!--begin::List Widget 3-->
					<div class="card card-xl-stretch mb-5 mb-xl-8">
						<!--begin::Header-->
						<div class="card-header border-0">
							<h3 class="card-title fw-bold text-dark">Todo</h3>
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
									<i class="ki-duotone ki-category fs-6">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</button>
								<!--begin::Menu 3-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
									<!--begin::Heading-->
									<div class="menu-item px-3">
										<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
									</div>
									<!--end::Heading-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Create Invoice</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link flex-stack px-3">Create Payment
										<span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
											<i class="ki-duotone ki-information fs-6">
												<span class="path1"></span>
												<span class="path2"></span>
												<span class="path3"></span>
											</i>
										</span></a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Generate Bill</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
										<a href="#" class="menu-link px-3">
											<span class="menu-title">Subscription</span>
											<span class="menu-arrow"></span>
										</a>
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Plans</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Billing</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Statements</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content px-3">
													<!--begin::Switch-->
													<label class="form-check form-switch form-check-custom form-check-solid">
														<!--begin::Input-->
														<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
														<!--end::Input-->
														<!--end::Label-->
														<span class="form-check-label text-muted fs-6">Recuring</span>
														<!--end::Label-->
													</label>
													<!--end::Switch-->
												</div>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3 my-1">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 3-->
								<!--end::Menu-->
							</div>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-2">
							<!--begin::Item-->
							<div class="d-flex align-items-center mb-8">
								<!--begin::Bullet-->
								<span class="bullet bullet-vertical h-40px bg-success"></span>
								<!--end::Bullet-->
								<!--begin::Checkbox-->
								<div class="form-check form-check-custom form-check-solid mx-5">
									<input class="form-check-input" type="checkbox" value="" />
								</div>
								<!--end::Checkbox-->
								<!--begin::Description-->
								<div class="flex-grow-1">
									<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Create FireStone Logo</a>
									<span class="text-muted fw-semibold d-block">Due in 2 Days</span>
								</div>
								<!--end::Description-->
								<span class="badge badge-light-success fs-8 fw-bold">New</span>
							</div>
							<!--end:Item-->
							<!--begin::Item-->
							<div class="d-flex align-items-center mb-8">
								<!--begin::Bullet-->
								<span class="bullet bullet-vertical h-40px bg-primary"></span>
								<!--end::Bullet-->
								<!--begin::Checkbox-->
								<div class="form-check form-check-custom form-check-solid mx-5">
									<input class="form-check-input" type="checkbox" value="" />
								</div>
								<!--end::Checkbox-->
								<!--begin::Description-->
								<div class="flex-grow-1">
									<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Stakeholder Meeting</a>
									<span class="text-muted fw-semibold d-block">Due in 3 Days</span>
								</div>
								<!--end::Description-->
								<span class="badge badge-light-primary fs-8 fw-bold">New</span>
							</div>
							<!--end:Item-->
							<!--begin::Item-->
							<div class="d-flex align-items-center mb-8">
								<!--begin::Bullet-->
								<span class="bullet bullet-vertical h-40px bg-warning"></span>
								<!--end::Bullet-->
								<!--begin::Checkbox-->
								<div class="form-check form-check-custom form-check-solid mx-5">
									<input class="form-check-input" type="checkbox" value="" />
								</div>
								<!--end::Checkbox-->
								<!--begin::Description-->
								<div class="flex-grow-1">
									<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Scoping & Estimations</a>
									<span class="text-muted fw-semibold d-block">Due in 5 Days</span>
								</div>
								<!--end::Description-->
								<span class="badge badge-light-warning fs-8 fw-bold">New</span>
							</div>
							<!--end:Item-->
							<!--begin::Item-->
							<div class="d-flex align-items-center mb-8">
								<!--begin::Bullet-->
								<span class="bullet bullet-vertical h-40px bg-primary"></span>
								<!--end::Bullet-->
								<!--begin::Checkbox-->
								<div class="form-check form-check-custom form-check-solid mx-5">
									<input class="form-check-input" type="checkbox" value="" />
								</div>
								<!--end::Checkbox-->
								<!--begin::Description-->
								<div class="flex-grow-1">
									<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">KPI App Showcase</a>
									<span class="text-muted fw-semibold d-block">Due in 2 Days</span>
								</div>
								<!--end::Description-->
								<span class="badge badge-light-primary fs-8 fw-bold">New</span>
							</div>
							<!--end:Item-->
							<!--begin::Item-->
							<div class="d-flex align-items-center mb-8">
								<!--begin::Bullet-->
								<span class="bullet bullet-vertical h-40px bg-danger"></span>
								<!--end::Bullet-->
								<!--begin::Checkbox-->
								<div class="form-check form-check-custom form-check-solid mx-5">
									<input class="form-check-input" type="checkbox" value="" />
								</div>
								<!--end::Checkbox-->
								<!--begin::Description-->
								<div class="flex-grow-1">
									<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Project Meeting</a>
									<span class="text-muted fw-semibold d-block">Due in 12 Days</span>
								</div>
								<!--end::Description-->
								<span class="badge badge-light-danger fs-8 fw-bold">New</span>
							</div>
							<!--end:Item-->
							<!--begin::Item-->
							<div class="d-flex align-items-center">
								<!--begin::Bullet-->
								<span class="bullet bullet-vertical h-40px bg-success"></span>
								<!--end::Bullet-->
								<!--begin::Checkbox-->
								<div class="form-check form-check-custom form-check-solid mx-5">
									<input class="form-check-input" type="checkbox" value="" />
								</div>
								<!--end::Checkbox-->
								<!--begin::Description-->
								<div class="flex-grow-1">
									<a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">Customers Update</a>
									<span class="text-muted fw-semibold d-block">Due in 1 week</span>
								</div>
								<!--end::Description-->
								<span class="badge badge-light-success fs-8 fw-bold">New</span>
							</div>
							<!--end:Item-->
						</div>
						<!--end::Body-->
					</div>
					<!--end:List Widget 3-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
			<!--begin::Tables Widget 5-->
			<div class="card mb-5 mb-xl-8">
				<!--begin::Header-->
				<div class="card-header border-0 pt-5">
					<h3 class="card-title align-items-start flex-column">
						<span class="card-label fw-bold fs-3 mb-1">Latest Products</span>
						<span class="text-muted mt-1 fw-semibold fs-7">More than 400 new products</span>
					</h3>
					<div class="card-toolbar">
						<ul class="nav">
							<li class="nav-item">
								<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 me-1 active" data-bs-toggle="tab" href="#kt_table_widget_5_tab_1">Month</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_5_tab_2">Week</a>
							</li>
							<li class="nav-item">
								<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4" data-bs-toggle="tab" href="#kt_table_widget_5_tab_3">Day</a>
							</li>
						</ul>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body py-3">
					<div class="tab-content">
						<!--begin::Tap pane-->
						<div class="tab-pane fade show active" id="kt_table_widget_5_tab_1">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
									<!--begin::Table head-->
									<thead>
										<tr class="border-0">
											<th class="p-0 w-50px"></th>
											<th class="p-0 min-w-150px"></th>
											<th class="p-0 min-w-140px"></th>
											<th class="p-0 min-w-110px"></th>
											<th class="p-0 min-w-50px"></th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad Simmons</a>
												<span class="text-muted fw-semibold d-block">Movie Creator</span>
											</td>
											<td class="text-end text-muted fw-bold">React, HTML</td>
											<td class="text-end">
												<span class="badge badge-light-success">Approved</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/telegram.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Popular Authors</a>
												<span class="text-muted fw-semibold d-block">Most Successful</span>
											</td>
											<td class="text-end text-muted fw-bold">Python, MySQL</td>
											<td class="text-end">
												<span class="badge badge-light-warning">In Progress</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/vimeo.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">New Users</a>
												<span class="text-muted fw-semibold d-block">Awesome Users</span>
											</td>
											<td class="text-end text-muted fw-bold">Laravel,Metronic</td>
											<td class="text-end">
												<span class="badge badge-light-primary">Success</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/bebo.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Active Customers</a>
												<span class="text-muted fw-semibold d-block">Movie Creator</span>
											</td>
											<td class="text-end text-muted fw-bold">AngularJS, C#</td>
											<td class="text-end">
												<span class="badge badge-light-danger">Rejected</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/kickstarter.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Bestseller Theme</a>
												<span class="text-muted fw-semibold d-block">Best Customers</span>
											</td>
											<td class="text-end text-muted fw-bold">ReactJS, Ruby</td>
											<td class="text-end">
												<span class="badge badge-light-warning">In Progress</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
							</div>
							<!--end::Table-->
						</div>
						<!--end::Tap pane-->
						<!--begin::Tap pane-->
						<div class="tab-pane fade" id="kt_table_widget_5_tab_2">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
									<!--begin::Table head-->
									<thead>
										<tr class="border-0">
											<th class="p-0 w-50px"></th>
											<th class="p-0 min-w-150px"></th>
											<th class="p-0 min-w-140px"></th>
											<th class="p-0 min-w-110px"></th>
											<th class="p-0 min-w-50px"></th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Brad Simmons</a>
												<span class="text-muted fw-semibold d-block">Movie Creator</span>
											</td>
											<td class="text-end text-muted fw-bold">React, HTML</td>
											<td class="text-end">
												<span class="badge badge-light-success">Approved</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/telegram.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Popular Authors</a>
												<span class="text-muted fw-semibold d-block">Most Successful</span>
											</td>
											<td class="text-end text-muted fw-bold">Python, MySQL</td>
											<td class="text-end">
												<span class="badge badge-light-warning">In Progress</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/bebo.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Active Customers</a>
												<span class="text-muted fw-semibold d-block">Movie Creator</span>
											</td>
											<td class="text-end text-muted fw-bold">AngularJS, C#</td>
											<td class="text-end">
												<span class="badge badge-light-danger">Rejected</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
							</div>
							<!--end::Table-->
						</div>
						<!--end::Tap pane-->
						<!--begin::Tap pane-->
						<div class="tab-pane fade" id="kt_table_widget_5_tab_3">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
									<!--begin::Table head-->
									<thead>
										<tr class="border-0">
											<th class="p-0 w-50px"></th>
											<th class="p-0 min-w-150px"></th>
											<th class="p-0 min-w-140px"></th>
											<th class="p-0 min-w-110px"></th>
											<th class="p-0 min-w-50px"></th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/kickstarter.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Bestseller Theme</a>
												<span class="text-muted fw-semibold d-block">Best Customers</span>
											</td>
											<td class="text-end text-muted fw-bold">ReactJS, Ruby</td>
											<td class="text-end">
												<span class="badge badge-light-warning">In Progress</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/bebo.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Active Customers</a>
												<span class="text-muted fw-semibold d-block">Movie Creator</span>
											</td>
											<td class="text-end text-muted fw-bold">AngularJS, C#</td>
											<td class="text-end">
												<span class="badge badge-light-danger">Rejected</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/vimeo.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">New Users</a>
												<span class="text-muted fw-semibold d-block">Awesome Users</span>
											</td>
											<td class="text-end text-muted fw-bold">Laravel,Metronic</td>
											<td class="text-end">
												<span class="badge badge-light-primary">Success</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<div class="symbol symbol-45px me-2">
													<span class="symbol-label">
														<img src="assets/media/svg/brand-logos/telegram.svg" class="h-50 align-self-center" alt="" />
													</span>
												</div>
											</td>
											<td>
												<a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">Popular Authors</a>
												<span class="text-muted fw-semibold d-block">Most Successful</span>
											</td>
											<td class="text-end text-muted fw-bold">Python, MySQL</td>
											<td class="text-end">
												<span class="badge badge-light-warning">In Progress</span>
											</td>
											<td class="text-end">
												<a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
													<i class="ki-duotone ki-arrow-right fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</a>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
							</div>
							<!--end::Table-->
						</div>
						<!--end::Tap pane-->
					</div>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Tables Widget 5-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-8">
				<!--begin::Col-->
				<div class="col-xl-6">
					<!--begin::Mixed Widget 8-->
					<div class="card card-xl-stretch mb-xl-8">
						<!--begin::Body-->
						<div class="card-body">
							<!--begin::Heading-->
							<div class="d-flex flex-stack">
								<!--begin:Info-->
								<div class="d-flex align-items-center">
									<!--begin:Image-->
									<div class="symbol symbol-60px me-5">
										<span class="symbol-label bg-danger-light">
											<img src="assets/media/svg/brand-logos/plurk.svg" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<!--end:Image-->
									<!--begin:Title-->
									<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
										<a href="#" class="text-dark fw-bold text-hover-primary fs-5">Monthly Subscription</a>
										<span class="text-muted fw-bold">Due: 27 Apr 2020</span>
									</div>
									<!--end:Title-->
								</div>
								<!--begin:Info-->
								<!--begin:Menu-->
								<div class="ms-1">
									<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-category fs-6">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</button>
									<!--begin::Menu 2-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator mb-3 opacity-75"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">New Ticket</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">New Customer</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
											<!--begin::Menu item-->
											<a href="#" class="menu-link px-3">
												<span class="menu-title">New Group</span>
												<span class="menu-arrow"></span>
											</a>
											<!--end::Menu item-->
											<!--begin::Menu sub-->
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="#" class="menu-link px-3">Admin Group</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="#" class="menu-link px-3">Staff Group</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="#" class="menu-link px-3">Member Group</a>
												</div>
												<!--end::Menu item-->
											</div>
											<!--end::Menu sub-->
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">New Contact</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator mt-3 opacity-75"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content px-3 py-3">
												<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
											</div>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu 2-->
								</div>
								<!--end::Menu-->
							</div>
							<!--end::Heading-->
							<!--begin:Stats-->
							<div class="d-flex flex-column w-100 mt-12">
								<span class="text-dark me-2 fw-bold pb-3">Progress</span>
								<div class="progress h-5px w-100">
									<div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<!--end:Stats-->
							<!--begin:Team-->
							<div class="d-flex flex-column mt-10">
								<div class="text-dark me-2 fw-bold pb-4">Team</div>
								<div class="d-flex">
									<a href="#" class="symbol symbol-35px me-2" data-bs-toggle="tooltip" title="Ana Stone">
										<img src="assets/media/avatars/300-6.jpg" alt="" />
									</a>
									<a href="#" class="symbol symbol-35px me-2" data-bs-toggle="tooltip" title="Mark Larson">
										<img src="assets/media/avatars/300-5.jpg" alt="" />
									</a>
									<a href="#" class="symbol symbol-35px me-2" data-bs-toggle="tooltip" title="Sam Harris">
										<img src="assets/media/avatars/300-9.jpg" alt="" />
									</a>
									<a href="#" class="symbol symbol-35px" data-bs-toggle="tooltip" title="Alice Micto">
										<img src="assets/media/avatars/300-10.jpg" alt="" />
									</a>
								</div>
							</div>
							<!--end:Team-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Mixed Widget 8-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-6">
					<!--begin::Mixed Widget 8-->
					<div class="card card-xl-stretch mb-xl-8">
						<!--begin::Body-->
						<div class="card-body">
							<!--begin::Heading-->
							<div class="d-flex flex-stack">
								<!--begin:Info-->
								<div class="d-flex align-items-center">
									<!--begin:Image-->
									<div class="symbol symbol-60px me-5">
										<span class="symbol-label bg-primary-light">
											<img src="assets/media/svg/brand-logos/vimeo.svg" class="h-50 align-self-center" alt="" />
										</span>
									</div>
									<!--end:Image-->
									<!--begin:Title-->
									<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">
										<a href="#" class="text-dark fw-bold text-hover-primary fs-5">Monthly Subscription</a>
										<span class="text-muted fw-bold">Due: 27 Apr 2020</span>
									</div>
									<!--end:Title-->
								</div>
								<!--begin:Info-->
								<!--begin:Menu-->
								<div class="ms-1">
									<button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-category fs-6">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
										</i>
									</button>
									<!--begin::Menu 2-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator mb-3 opacity-75"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">New Ticket</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">New Customer</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
											<!--begin::Menu item-->
											<a href="#" class="menu-link px-3">
												<span class="menu-title">New Group</span>
												<span class="menu-arrow"></span>
											</a>
											<!--end::Menu item-->
											<!--begin::Menu sub-->
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="#" class="menu-link px-3">Admin Group</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="#" class="menu-link px-3">Staff Group</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="#" class="menu-link px-3">Member Group</a>
												</div>
												<!--end::Menu item-->
											</div>
											<!--end::Menu sub-->
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">New Contact</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator mt-3 opacity-75"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content px-3 py-3">
												<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
											</div>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu 2-->
								</div>
								<!--end::Menu-->
							</div>
							<!--end::Heading-->
							<!--begin:Stats-->
							<div class="d-flex flex-column w-100 mt-12">
								<span class="text-dark me-2 fw-bold pb-3">Progress</span>
								<div class="progress h-5px w-100">
									<div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
							<!--end:Stats-->
							<!--begin:Team-->
							<div class="d-flex flex-column mt-10">
								<div class="text-dark me-2 fw-bold pb-4">Team</div>
								<div class="d-flex">
									<a href="#" class="symbol symbol-35px me-2" data-bs-toggle="tooltip" title="Ana Stone">
										<img src="assets/media/avatars/300-6.jpg" alt="" />
									</a>
									<a href="#" class="symbol symbol-35px me-2" data-bs-toggle="tooltip" title="Mark Larson">
										<img src="assets/media/avatars/300-5.jpg" alt="" />
									</a>
									<a href="#" class="symbol symbol-35px me-2" data-bs-toggle="tooltip" title="Sam Harris">
										<img src="assets/media/avatars/300-9.jpg" alt="" />
									</a>
									<a href="#" class="symbol symbol-35px" data-bs-toggle="tooltip" title="Alice Micto">
										<img src="assets/media/avatars/300-10.jpg" alt="" />
									</a>
								</div>
							</div>
							<!--end:Team-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Mixed Widget 8-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
		</div>
		<!--end::Col-->
	</div>
	<!--end::Row-->
@endsection
	