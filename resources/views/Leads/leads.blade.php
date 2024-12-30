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
                    <li><a class="dropdown-item" data-action_type="ADD_CALL_RESULT" href="#">Add Call Result</a></li>
                    <li><a class="dropdown-item" data-action_type="ADD_APPOINTMENT" href="#">App</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>
    <div class="col-md-12">
        <div class="row align-items-center">
                <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group row align-items-center">
                                <div class="col-md-4">
                                    <label>&nbsp;</label>
                                            <div class="d-flex align-items-center">
                                                <!--begin::Input group-->
                                                <div class="position-relative w-md-400px me-md-2">
                                                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                        </i>
                                                        <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                </div>
                                <div class="col-md-4">

                                     <div class="form-group">
                                      <label>Salesman <span class="required"> * </span> </label>
                                      <select name="cl_sales_id" required="required" id="CL_SALES_ID"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
                                              <option value="">-- Select User --</option>
                                              <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                                      <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                              <?php  } ?>
                                      </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                                <select   id="LEAD_STATUS" name="lead_status" class="form-control form-select" data-control="select2" data-placeholder="Select Lead Status">
                                                        <option value="0">-- Select Status --</option>
                                                @foreach($lead_statuses as $index => $status_info)
                                                  <option value="{{ $status_info->ls_id }}">{{ $status_info->ls_status_title }}</option>
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
                                          <div class="row">
                                              <div class="col-md-12" style="height:25px">&nbsp;</div>
                                          </div>
								<div class="table-responsive">
									<table class="table table-bordered">
                                                                                    <thead>
                                                                                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                                                                    <th title="#"></th>
                                                                                                    <th title="Id"> ID </th>
                                                                                                    <th title="RS#"> RS# </th>
                                                                                                    <th title="Lead name"> Lead Name </th>
                                                                                                    <th title="Lead name"> Address </th>
                                                                                                    <th title="Lead name"> Leads Type </th>
                                                                                                    <th title="Lead name"> Salesman </th>
                                                                                                    <th title="Lead name"> Telemarketer </th>
                                                                                                    <th title="Mobile"> Mobile </th>
                                                                                                    <th title="Mobile"> Referred by </th>
                                                                                                    <th>Result</th>
                                                                                                    <th>Notes</th>
                                                                                                    <th style="width:2px;" nowrap title="#"> edit </th>
                                                                                                    <th style="width:2px;" nowrap title="#"> Delete </th>
                                                                                            </tr>
                                                                                    </thead>
                                                                                    <tbody id="LstLeads">

                                                                                    </tbody>
                                                                        </table>
								</div>

                                                    <div class="row">
                                                      <div class="col-md-10 col-lg-10 col-xs-10" align="left">
                                                          <ul id="LeadsPagination" class="pagination-sm"></ul>
                                                      </div>
                                                        <div class="col-md-2 col-lg-2 col-xs-2" align="right"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" style="height:50px" align="right"></div>
                                                    </div>
                                          
                                          
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
                                                            <div class="row">
                                                                <div class="col-md-12" style="height:150px" align="right"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12" >
                                                                    <table class="table table-rounded table-striped border gy-7 gs-7">
                                                                                    <thead>
                                                                                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                                                                    <th title="#"></th>
                                                                                                    <th title="Id"> ID </th> 
                                                                                                    <th title="Salesman"> Salesman </th>
                                                                                                    <th title="Telemarketer"> Telemarketer </th>
                                                                                                    <th title="Next Call"> Next Call </th>
                                                                                                    <th title="Notes"> Notes </th>
                                                                                                    <th title="Results"> Results </th>
                                                                                            </tr>
                                                                                    </thead>
                                                                                    <tbody id="LstLeadResults">

                                                                                    </tbody>
                                                                        </table>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12" style="height:50px" align="right"></div>
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
                                                                            <option value="{{ $ls_info->ls_id }}">{{ $ls_info->ls_status_title }}</option>
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
                                        
                                        
<div class="modal fade" id="AddResultModel" tabindex="-1" role="dialog" aria-labelledby="AddResultModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title" id="AddResultModelLabel">
                                    Add Lead Result Call
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">
                                            &times;
                                    </span>
                            </button>
                    </div>
                    <div class="modal-body">
                            <form name="frm_add_result" id="FRM_ADD_RESULT">
                                    <span id="hidden_field">
                                            <input type="hidden" name="lr_lead_ids" value="" />
                                              {!! csrf_field() !!}
                                    </span>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label">Next Call Date</label><br/>
                                                <input type="text" name="lr_next_date" id="LR_NEXT_DATE" class="form-control" value="" />
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Result</label> 
                                                      <select name="lr_text_result" id="LR_TEXT_RESULT" class="form-control form-select" data-control="select2" data-placeholder="Select Apt Result">
                                                          <option value="">-- Select Apt Result --</option>
                                                          <?php foreach ( $lst_appt_results as $key => $res_info ) { ?>
                                                                  <option  value="<?php echo $res_info->ar_id;  ?>"><?php echo $res_info->ar_app_result;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                         <div class="col-12">
                                            <div class="form-group">
                                                <label class="control-label">Notes</label><br/>
                                                <textarea class="form-control" name="cl_lead_notes" id="LR_TEXT_NOTES" style="width:100%;height:250px;resize:none" ></textarea>
                                            </div>
                                        </div>
                                    </div> 
                            </form>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                            </button>
                            <button type="button" name="btn_add_result" id="BTN_ADD_RESULT" class="btn btn-primary">
                                    Submit
                            </button>
                    </div>
            </div>
    </div>
</div>
@endsection