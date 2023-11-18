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
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Leads Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              		<li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                    <li><a class="dropdown-item" data-action_type="IMPORT" href="#">Import</a></li>
                    <li><a class="dropdown-item" data-action_type="DOWNLOAD_TEMPLATE" href="#">Download Import Template</a></li>
                    <li><a class="dropdown-item" data-action_type="ASSIGN_LEAD" href="#">Assign Lead</a></li>
                    <li><a class="dropdown-item" data-action_type="CHANGE_STATUS" href="#">Change Lead Status</a></li>
                    <li><a class="dropdown-item" data-action_type="CONVERT_LEAD_ACCOUNT" href="#">Convert Lead to Account</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
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
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('crm/leads/addform') }}" class="btn btn-info">
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
								<div class="table-responsive">
									<table class="table" width="100%">
                            		<thead>
                						<tr class="fw-bold fs-6 text-gray-800">
                            				<th title="#">#</th>
                            				<th title="Id"> ID </th>
                            				<th title="Lead name"> Lead Name </th>
                            				<th title="Company"> Company </th>
                            				<th title="Mobile"> Mobile </th>
                            				<th title="Email"> Email </th>
                            				<th style="width:2px;" nowrap title="#"> edit </th>
                            				<th style="width:2px;" nowrap title="#"> Delete </th>
                            			</tr>
                            		</thead>
                            		<tbody id="LstLeads">
                            
                            		</tbody>
                            </table>
								</div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-md-12" align="right">
										<a href="{{ url('crm/leads/addform') }}" class="btn btn-info">
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