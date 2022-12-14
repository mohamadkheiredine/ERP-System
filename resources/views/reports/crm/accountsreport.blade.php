<?php
/***********************************************************
accountsreport.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 11, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Reports Management > Accounts Report"])

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
<script type="text/javascript" src="{{ url('js/modules/reports.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/reports/accountsreport.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Reports Management > Accounts Report
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
																		<a href="" class="m-nav__link">
																			<i class="m-nav__link-icon flaticon-share"></i>
																			<span class="m-nav__link-text">
																				Print
																			</span>
																		</a>
																	</li>
																	<li class="m-nav__item">
																		<a href="" class="m-nav__link">
																			<i class="m-nav__link-icon flaticon-chat-1"></i>
																			<span class="m-nav__link-text">
																				Export As CSV
																			</span>
																		</a>
																	</li>
																	<li class="m-nav__item">
																		<a href="" class="m-nav__link">
																			<i class="m-nav__link-icon flaticon-multimedia-2"></i>
																			<span class="m-nav__link-text">
																				Import
																			</span>
																		</a>
																	</li>
																	<li class="m-nav__item">
																		<a href="" class="m-nav__link">
																			<i class="m-nav__link-icon flaticon-multimedia-2"></i>
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
								<!--begin: Search Form -->
								<form name="frm_search_report">
								 {!! csrf_field() !!}
								<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
									<div class="row align-items-center">
										<div class="col-xl-12 order-2 order-xl-1">
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
                                                   <div class="m-input-icon m-input-icon--left">
                                                   			<label>Account Category :</label><br/>
                                                    		<select class="m-bootstrap-select m_selectpicker form-control" id="ACCOUNT_CATEGORY" multiple="multiple"  name="account_category[]">
                                                    			<option value="0">-- Select Category --</option>
                                                                @foreach($lst_account_categories as $index => $cat_info)
                                                                  <option value="{{ $cat_info->cc_id }}">{{ $cat_info->cc_category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
												<div class="col-md-4">
                                                  <div class="m-input-icon m-input-icon--left">
                                                  			<label>Account Leads :</label><br/>
                                                    		<select class="m-bootstrap-select m_selectpicker form-control" id="ACCOUNT_LEAD" multiple="multiple"  name="account_lead[]">
                                                    			<option value="0">-- Select Leads --</option>
                                                                @foreach($lst_leads as $index => $leads_info)
                                                                  <option value="{{ $leads_info->cl_id }}">{{ $leads_info->cl_first_name . " " . $leads_info->cl_last_name  }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
												<div class="col-md-12">&nbsp;</div>
												<div class="col-md-4">
                                                  <div class="m-input-icon m-input-icon--left">
                                                  			<label>Account Owner :</label><br/>
                                                    		<select class="m-bootstrap-select m_selectpicker form-control" id="ACCOUNT_OWNER" multiple="multiple"  name="account_owner[]">
                                                    			<option value="0">-- Select Account Owner --</option>
                                                                @foreach($lst_users as $index => $user_info)
                                                                  <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
												<div class="col-md-4">
                                                  <div class="m-input-icon m-input-icon--left">
                                                 			 <label>Account Industry :</label><br/>
                                                    		<select class="m-bootstrap-select m_selectpicker form-control" id="ACCOUNT_INDUSTRY" multiple="multiple"  name="account_industry[]">
                                                    			<option value="0">-- Select Account Industry --</option>
                                                                @foreach($lst_industries as $index => $ind_info)
                                                                  <option value="{{ $ind_info->si_id }}">{{ $ind_info->si_industry }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
												<div class="col-md-4">

												</div>
												<div class="col-md-12">&nbsp;</div>
												<div class="col-md-4">
                                                  <div class="m-input-icon m-input-icon--left">
                                                    		<label>Date From:</label><br/>
                                                    		 <input type="text" name='account_date_from' class="form-control" id="ACCOUNT_DATE_FROM" value="" />
                                                    </div>
												</div>
												<div class="col-md-4">
                                                  <div class="m-input-icon m-input-icon--left">
                                                    	<label>Date To:</label><br/>
                                                    	<input type="text" name='account_date_to' class="form-control" id="ACCOUNT_DATE_TO" value="" />
                                                    </div>
												</div>
												<div class="col-md-12" align="right">
                                                  	<button type="button" name="btn_report" id="BTN_REPORT" class="btn btn-info">Show Report</button>
												</div>
											</div>
										</div> 
									</div>
								</div>
								</form>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="LstAccountsReportGrid">
									 
								</div>
								<!--end: Datatable -->
								 
							</div>
						</div>
@endsection