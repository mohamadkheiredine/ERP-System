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

.fixed-header-table {
    border-collapse: collapse;
    width: 100%;
}

.fixed-header-table thead {
    background-color: #f9f9f9;
    position: sticky;
    top: 0;
    z-index: 2;
}

.table-scroll-wrapper {
    max-height: 300px;
    overflow-y: auto;
}

.fixed-header-table th,
.fixed-header-table td {
    white-space: nowrap;
    padding: 8px;
    border: 1px solid #dee2e6;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/leadsmanagement.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.querySelector('.fixed-header-table');
        const headers = table.querySelectorAll('th[data-sort]');
        const tbody = table.querySelector('tbody');

        let sortColumn = null;
        let sortDirection = 1; // 1 for ascending, -1 for descending

        headers.forEach((header, i) => {
            header.style.cursor = "pointer";
            header.addEventListener("click", function () {
                const type = header.getAttribute('data-sort');
                sortDirection = (sortColumn === i) ? -sortDirection : 1;
                sortColumn = i;
                sortTableByColumn(tbody, i, sortDirection);
                // Optional: Show sort arrow
                headers.forEach(h => h.innerHTML = h.innerText); // Reset
            });
        });

        function sortTableByColumn(tbody, column, direction) {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((a, b) => {
                let cellA = a.children[column].innerText.trim();
                let cellB = b.children[column].innerText.trim();

                // Try to compare as numbers if possible
                if (!isNaN(cellA) && !isNaN(cellB)) {
                    cellA = Number(cellA);
                    cellB = Number(cellB);
                }
                // Try to compare as dates if the column is "Last Call Date" or similar
                else if (column === 11 || column === 14) { // update these indexes for date columns
                    cellA = new Date(cellA);
                    cellB = new Date(cellB);
                }
                return (cellA > cellB ? 1 : cellA < cellB ? -1 : 0) * direction;
            });
            rows.forEach(row => tbody.appendChild(row));
        }
    });
</script>

@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Leads Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" style="display:none" aria-expanded="false">
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
                                                        <input type="text" class="form-control form-control-solid ps-10" name="lead_name" id="LeadName" value="" placeholder="Lead Name" />
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label>
                                            <div class="d-flex align-items-center">
                                                <!--begin::Input group-->
                                                <div class="position-relative w-md-400px me-md-2">
                                                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                        </i>
                                                        <input type="text" class="form-control form-control-solid ps-10" name="referred_by" id="LeadRegion" value="" placeholder="Referred By" />
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                </div>
                            <div class="col-md-4">
                                    <label>&nbsp;</label>
                                            <div class="d-flex align-items-center">
                                                <!--begin::Input group-->
                                                <div class="position-relative w-md-400px me-md-2">
                                                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                        </i>
                                                        <input type="text" class="form-control form-control-solid ps-10" name="lead_mobile" id="LeadMobile" value="" placeholder="Lead Mobile" />
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                </div>
                            <div class="col-md-4">
                                    <label>&nbsp;</label>
                                            <div class="d-flex align-items-center">
                                                <!--begin::Input group-->
                                                <div class="position-relative w-md-400px me-md-2">
                                                        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                        </i>
                                                        <input type="text" class="form-control form-control-solid ps-10" name="sheet_number" id="SheetNumber" value="" placeholder="Sheet Number" />
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Area <span class="required"> * </span> </label>
                                        <select name="cl_area" required="required" id="CL_AREA"  tabindex="5"  class="form-control form-select" data-control="select2" data-placeholder="Select Area">
                                            <option value="0">-- Select Area --</option>
                                            <?php foreach ( $lst_areas as $key => $area_info ) { ?>
                                            <option value="<?php echo $area_info->la_area;  ?>"><?php echo $area_info->la_area;  ?></option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Region</label>
                                        <div class="col-md-12" id="REGION_DROPDOWN">
                                            <select name="cl_region" required="required"  id="CL_REGION" class="form-control form-select" tabindex="5" data-control="select2" data-placeholder="Select Region">
                                                <option value="">-- Select Region --</option>
                                                @foreach( $lst_regions as $key => $region_info )
                                                    <option value="{{ $region_info->lr_region }}">{{ $region_info->lr_region }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                      <label>Lead Types </label>
                                      <select name="cl_lead_types"  id="CL_LEAD_TYPES"  class="form-control form-select" data-control="select2" data-placeholder="Lead Types">
                                              <option value="0">-- Select Types --</option>
                                              <?php foreach ( $lst_lead_types as $key => $type_info ) { ?>
                                                      <option value="<?php echo $type_info->lt_id;  ?>"><?php echo $type_info->lt_deal_type;  ?></option>
                                              <?php  } ?>
                                      </select>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="form-group">
                                      <label>Lead Last Result </label>
                                      <select name="cl_lead_result"  id="CL_LEAD_RESULT"  class="form-control form-select" data-control="select2" data-placeholder="Lead Last Result">
                                              <option value="0">-- Select Result --</option>
                                              <?php foreach ( $lst_appt_results as $key => $re_info ) { ?>
                                                      <option value="<?php echo $re_info->ar_id;  ?>"><?php echo $re_info->ar_app_result;  ?></option>
                                              <?php  } ?>
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
                                                <div class="table-scroll-wrapper">
                                                    <table class="table fixed-header-table">
                                                        <thead class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <tr>
                                                            <th title="#" data-sort="number"></th>
                                                            <th title="index" data-sort="index">Index</th>
                                                            <th title="RS#" data-sort="rs"> RS# </th>
                                                            <th title="Lead name" data-sort="leadName"> Lead Name </th>
                                                            <th title="Area" data-sort="area"> Area </th>
                                                            <th title="Region" data-sort="region"> Region </th>
                                                            <th title="Lead name" data-sort="leadType"> Leads Type </th>
                                                            <th title="Lead name" data-sort="salesman"> Salesman </th>
                                                            <th title="Lead name" data-sort="telemarketer"> Telemarketer </th>
                                                            <th title="Mobile" data-sort="mobile"> Mobile </th>
                                                            <th title="Referred By" data-sort="referredBy"> Referred by </th>
                                                            <th title="Last Call Date" data-sort="lastCallDate"> Last Call Date </th>
                                                            <th title="Result" style="cursor: pointer" id="btnAddResult" data-sort="result">Result</th>
                                                            <th title="Last Result" data-sort="lastResult">Last Result</th>
                                                            <th title="Next Call" data-sort="nextCall">Next Call</th>
                                                            <th>Notes</th>
                                                            <th style="width:2px;" nowrap title="#"> edit </th>
                                                            <th style="width:2px;" nowrap title="#"> Delete </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="LstLeads">
                                                        <!-- Table rows here -->
                                                        </tbody>
                                                    </table>
                                                </div>
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
                                                                <div class="col-md-12" style="height:50px" align="right"></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12" >
                                                                    <table class="table table-rounded table-striped border gy-7 gs-7">
                                                                                    <thead>
                                                                                            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                                                                    <th title="Salesman"> Salesman </th>
                                                                                                    <th title="Telemarketer"> Telemarketer </th>
                                                                                                    <th title="Next Call"> Next Call </th>
                                                                                                    <th title="Next Call"> Result Date </th>
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
										<button type="button" name="btn_close_changestatus" class="btn btn-secondary" data-dismiss="modal">
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
										<button type="button" name="btn_close_assign" class="btn btn-secondary" data-dismiss="modal">
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
                            <button type="button" name="btn_result_close" class="btn btn-secondary" data-dismiss="modal">
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
