<?php
/***********************************************************
defaultaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to manage default accounts 
***********************************************************/

?>



@extends('layouts.layout',['page_title' => "Default Accounts"])

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
<script type="text/javascript" src="{{ url('js/modules/defaultaccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/defaultaccounts.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
<div class="m-portlet__head">
	<div class="m-portlet__head-caption">
		<div class="m-portlet__head-title">
			<h3 class="m-portlet__head-text">
				Default Accounts
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
			<div class="col-xl-8 order-2 order-xl-1">
				<div class="form-group m-form__group row align-items-center">
					<div class="col-md-4">
					<div class="m-input-icon m-input-icon--left">
							<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
							<span class="m-input-icon__icon m-input-icon__icon--left">
								<span>
									<i class="la la-search"></i>
								</span>
							</span>
						</div>

					</div>
					<div class="col-md-4">
                        <div class="d-md-none m--margin-bottom-10"></div>
					</div>
					<div class="col-md-4">
                        <div class="d-md-none m--margin-bottom-10"></div>
					</div>
				</div>
			</div>
			<div class="col-xl-12 order-1 order-xl-12 m--align-right">
				<div class="m-separator m-separator--dashed d-xl-none"></div>
			</div>
		</div>
	</div>
	<!--end: Search Form -->
      <!--begin: Datatable -->
      <form name="frm_save_accounts" id="FRM_SAVE_ACCOUNTS">
                  {!! csrf_field() !!}
    		<div id="LstDefaultAccounts" class="m_datatable">
		 
			</div>  
      </form>
	<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
		<div class="m-separator m-separator--dashed d-xl-none"></div>
	</div>
	<!--end: Datatable -->
	<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
			<button type="button" name="btn_save_info" class="btn btn-info" >Save Info</button>
		</div>
	</div>
</div>

@endsection