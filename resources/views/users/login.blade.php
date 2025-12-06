<?php
/***********************************************************
login.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Aug 31, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    ITM SOLUTIONS COPYRIGHT 2018

Page Description :
$company_info
***********************************************************/


$image_src_url  = url('/')."/".Config::get('constants.COMPANY_PATH').$company_info[0]['cd_logo_base_src'].$company_info[0]['cd_logo_file_name'].".".$company_info[0]['cd_logo_file_extension'];
$image_src_path = public_path(). "/" .Config::get('constants.COMPANY_PATH').$company_info[0]['cd_logo_base_src'].$company_info[0]['cd_logo_file_name'].".".$company_info[0]['cd_logo_file_extension'];

if(strlen($company_info[0]['cd_logo_base_src']) > 0 ){
    $img_src = $image_src_url;
}else{
    $img_src = url('images/logo.png');
}

?>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>
			{{ strlen($company_info[0]['cd_company_name']) > 0 ? $company_info[0]['cd_company_name'] : "TITAN" }} ERP - LOGIN
		</title>
		<meta charset="utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="canonical" href="https://itmsolutionsmena.com" />
		<link rel="shortcut icon" href="{{ url('theme/src/assets/media/logos/favicon.ico') }}" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="{{ url('theme/style/src/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ url('theme/style/src/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="auth-bg">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
					<!--begin::Form-->
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<!--begin::Wrapper-->
						<div class="w-lg-500px p-10">
							<!--begin::Form-->
							<form class="form w-100" novalidate="novalidate" id="m_login" data-kt-redirect-url="#" action="#">
							 <span id="hidden_fields">
                                <input type="hidden" name="base_url" id="BASE_URL" value="{{ url('/') }}" />
                                {!! csrf_field() !!}
                            </span>
								<!--begin::Heading-->
								<div class="text-left mb-11">
                                                                        <a href="#" class="mb-0 mb-lg-12">
							<img alt="Logo" src="{{ url('images/titanerp.svg') }}" class="h-60px h-lg-75px" />
						</a>
								</div>
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Email-->
									<input placeholder="UserName" name="username" autocomplete="off" class="form-control bg-transparent fieldlogin" />
									<!--end::Email-->
								</div>
								<!--end::Input group=-->
								<div class="fv-row mb-3">
									<!--begin::Password-->
									<input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent fieldlogin" />
									<!--end::Password-->
								</div>
								<!--end::Input group=-->

								<!--begin::Submit button-->
								<div class="d-grid mb-10" style="text-align: left">
									<button type="submit" id="kt_sign_in_submit" class="btn btnLogin">
										<!--begin::Indicator label-->
										<span class="indicator-label">Sign In</span>
										<!--end::Indicator label-->
										<!--begin::Indicator progress-->
										<span class="indicator-progress">Please wait...
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										<!--end::Indicator progress-->
									</button>
								</div>
								<!--end::Submit button-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Form-->
				</div>
				<!--end::Body-->
				<!--begin::Aside-->
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ url('images/titanerpbackground.jpg') }})">
					<!--begin::Content-->

					<!--end::Content-->
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--end::Main-->

		<script src="{{ url('theme/style/src/assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ url('theme/style/src/assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ url('default/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('default/assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
<!-- 		<script src="{{ url('theme/style/src/assets/js/custom/authentication/sign-in/general.js') }}"></script> -->
<script src="{{ url('default/assets/snippets/pages/user/login.js') }}" type="text/javascript"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
