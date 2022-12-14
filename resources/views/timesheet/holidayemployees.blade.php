<?php
/***********************************************************
holidayemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Timesheet Management"])

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
<script type="text/javascript" src="{{ url('js/modules/timesheetmanagement.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/holidayemployees.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Holiday Employees
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
							  <div class="form-group">
                                <label class="control-label"> Year <span class="required"> * </span></label><br/>
                                <select name="ts_year" class="form-control" id="TS_YEAR">
                                	<option value="{{ date('Y') - 3 }}">{{ date('Y') - 3 }}</option>
                                	<option value="{{ date('Y') - 2 }}">{{ date('Y') - 2 }}</option>
                                	<option value="{{ date('Y') - 1 }}">{{ date('Y') - 1 }}</option>
                                	<option selected="selected" value="{{ date('Y') }}">{{ date('Y') }}</option>
                                </select>
                            </div>
						</div>
						<div class="col-md-4">
						     &nbsp;
						</div>
						<div class="col-md-4">
							<label class="control-label">&nbsp;</label><br/>
                            <button type="button" name="btn_search" class="btn btn-primary m-btn--wide"> Search </button>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
				
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="m_datatable" id="LstHolidayEmployees">

		</div>
		<!--end: Datatable -->
	</div>
</div>
@endsection