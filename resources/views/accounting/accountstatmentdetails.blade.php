<?php
/***********************************************************
accountstatment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 28, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Report Page for Account Statment
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Accounting Reports"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
.grouprow{
	cursor: pointer;
}
.Transaction{
	cursor: pointer;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/transactions.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/statmentdetails.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Account Statment Details
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
												<a href="#" class="m-nav__link printAccountStatment">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="#" class="m-nav__link quickactions">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
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
				<div class="col-xl-12 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
							<div class="form-group">
                                <label> Search Key </label>
                                <input type="text" name="search_query" id="SEARCH_QUERY" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label> From Date </label><br/>
                                <input type="text" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label> To Date </label><br/>
                                <input type="text" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group"><br/>
                                <label> <input type="checkbox" name="ck_include_before" id="CK_INCLUDE_BEFORE" value="1" />Including Before </label>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label> Order By </label><br/>
                                 <select name="order_by" id="ORDER_BY" class="form-control">
                                 	<option value="">Default Order</option>
                                 	<option value="transaction_date">Transaction Date</option>
                                 	<option value="creation_date">Creation Date</option>
                                 </select>
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					
				</div>
			</div>
		</div>
		<input type="hidden" name="detail_account_id" value="" />
		<input type="hidden" name="sel_currency_id" value="" />
		<input type="hidden" name="fisical_year" value="{{ date('Y') }}" />
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="col-md-12" id="LstAccountStatment">

		</div>
		<!--end: Datatable -->
	</div>
</div>
@endsection