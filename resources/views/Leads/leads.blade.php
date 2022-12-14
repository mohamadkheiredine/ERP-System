<?php
/***********************************************************
leads.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 22, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to Manage Leads
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
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/leadsmanagement.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Leads Management
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
																	<li class="m-nav__item">
																		<a data-action_type="ASSIGN_LEAD" href="#" class="m-nav__link quickactions">
																			<i class="m-nav__link-icon flaticon-users"></i>
																			<span class="m-nav__link-text">
																				Assign Leads
																			</span>
																		</a>
																	</li> 
																	<li class="m-nav__item">
																		<a data-action_type="CHANGE_STATUS" href="#" class="m-nav__link quickactions">
																			<i class="m-nav__link-icon la la-check-circle"></i>
																			<span class="m-nav__link-text">
																				Change Lead Status
																			</span>
																		</a>
																	</li> 
																	<li class="m-nav__item">
																		<a data-action_type="CONVERT_LEAD_ACCOUNT" href="#" class="m-nav__link quickactions">
																			<i class="m-nav__link-icon flaticon-business"></i>
																			<span class="m-nav__link-text">
																				Convert Lead to Account
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
														<span class="m-input-icon__icon m-input-icon__icon--right">
															<span>
																<i class="la la-search"></i>
															</span>
														</span>
													</div>

												</div>
												<div class="col-md-4">
                                                    <div class="m-input-icon m-input-icon--left">
                                                    		<select class="bs-select form-control" id="LEAD_CATEGORY" name="lead_category">
                                                    			<option value="0">-- Select Category --</option>
                                                                @foreach($lead_categories as $index => $cat_info)
                                                                  <option value="{{ $cat_info->cc_id }}">{{ $cat_info->cc_category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
												<div class="col-md-4">
                                                    <div class="m-input-icon m-input-icon--left">
                                                    		<select class="bs-select form-control" id="LEAD_STATUS" name="lead_status">
                                                    			<option value="0">-- Select Status --</option>
                                                                @foreach($lead_statuses as $index => $status_info)
                                                                  <option value="{{ $status_info->ls_id }}">{{ $status_info->ls_status_title }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
												<div class="col-md-4" style="margin-top:10px;">
                                                    <div class="m-input-icon m-input-icon--left">
                                                    		<select class="bs-select form-control" id="LEAD_USER" name="lead_user">
                                                    			<option value="0">--All Users--</option>
                                                                @foreach($lst_users as $index => $user_info)
                                                                  <option {{ $user_info->id == session("user_id") ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 m--align-right">
											<a href="{{ url('crm/leads/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
												<span>
													<i class="flaticon-tabs"></i>
													<span>
														New Lead
													</span>
												</span>
											</a>
											<div class="m-separator m-separator--dashed d-xl-none"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="m_datatable" id="LstLeads">

								</div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-md-12" align="right">
										<a href="{{ url('crm/leads/addform') }}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
												<span>
													<i class="flaticon-tabs"></i>
													<span>
														New Lead
													</span>
												</span>
											</a>
									</div>
								</div>
							</div>
						</div>
						
					<!-- Models Section -->
					<div class="modal fade" id="ChangeStatusModel" tabindex="-1" role="dialog" aria-labelledby="ChangeStatusModelLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="ChangeStatusModelLabel">
											Lead Change Status
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
										<form name="frm_change_status" id="FRM_CHANGE_STATUS">
											<span id="hidden_field">
												<input type="hidden" name="cs_lead_ids" value="" />
												  {!! csrf_field() !!}
											</span>
											<div class="row">
												<div class="col-12">
        											<div class="form-group">
        												    <label class="control-label">Lead Status <span class="required"> * </span></label><br/>
                                                            <select class="bs-select form-control" style="width:100%"  required="required" name="cs_lead_status_id" id="CS_LEAD_STATUS_ID" data-actions-box="true">
                                                                    <option value="">-- Select Status --</option>
                                                                    @foreach ($lead_statuses as $ls_index => $ls_info )
                                                                            <option value="{{ $ls_info->ls_id }}">{{ $ls_info->ls_status }}</option>
                                                                    @endforeach
                                                            </select>			
                                        
                                        				</div>
        											</div>
											</div> 
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button type="button" name="btn_change_status" class="btn btn-primary">
											Submit
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="modal fade" id="AssignLeadModel" tabindex="-1" role="dialog" aria-labelledby="AssignLeadModelLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="AssignLeadModelLabel">
											Leads Assign to
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
										<form name="frm_lead_assign_to" id="FRM_LEAD_ASSIGN_TO">
											<span id="hidden_field">
												<input type="hidden" name="la_lead_ids" value="" />
												  {!! csrf_field() !!}
											</span>
											<div class="row">
												<div class="col-12">
        											<div class="form-group">
        												    <label class="control-label">Lead Assign to <span class="required"> * </span></label><br/>
                                                            <select class="bs-select form-control" style="width:100%"  required="required" name="la_fk_assign_to" id="LA_FK_ASSIGN_TO" data-actions-box="true">
                                                                    <option value="">-- Select Assign To --</option>
                                                                    @foreach ($lst_users as $u_index => $user_info )
                                                                            <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                    @endforeach
                                                            </select>			
                                        
                                        				</div>
        											</div>
											</div> 
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button type="button" name="btn_assign_lead_to" class="btn btn-primary">
											Submit
										</button>
									</div>
								</div>
							</div>
						</div>
					<!-- End Models Section -->
@endsection