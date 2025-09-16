<?php
/***********************************************************
inboundcalls.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
Cost Center Categories Management
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Pending Calls Management"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
</style>
<link href="{{ url('default/assets/plugins/tablesorter/dist/css/theme.default.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('default/assets/plugins/tablesorter/dist/js/jquery.tablesorter.js') }}"></script>
<script type="text/javascript" src="{{ url('default/assets/plugins/tablesorter/dist/js/jquery.tablesorter.widgets.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/inboundcalls.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/inboundcalls.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Call's Management</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
                                   <li><a class="dropdown-item" data-action_type="ADD_MAINTENANCE_VOUCHER" href="#">Add Maintenance Voucher</a></li>
                                   <li><a class="dropdown-item" data-action_type="DOWNLOAD_PDF_REPORT" href="#">Download PDF Report</a></li>
                                   <li><a class="dropdown-item" data-action_type="ADD_RESULT" href="#">Add Call Result</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
		<span id="hidden_fields">
			<input type="hidden" name="page_number" value="1" />
		</span>
		<!--begin: Search Form -->
		<div class="form">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
								<label>&nbsp;</label>
							<div class="d-flex align-items-center">
								<!--begin::Input group-->
								<div class="position-relative w-md-400px me-md-2">
									<i
										class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
										<span class="path1"></span> <span class="path2"></span>
									</i> <input type="text"
										class="form-control form-control-solid ps-10"
										name="general_search" id="generalSearch" value=""
										placeholder="Search" />
								</div>
								<!--end::Input group-->
							</div>

						</div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Technician</label>
                                                    <select name="ic_technician_id" id="IC_TECHNICIAN_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Technician">
                                                           <option value="">-- Select Technician --</option>
                                                           @foreach ( $lst_technicians as $key => $user_info )
                                                                   <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                           @endforeach
                                                           @foreach ( $lst_admins as $key => $user_info )
                                                                   <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                           @endforeach
                                                   </select>
                                                </div>
						<div class="col-md-4">
                                                    <label class="control-label">Maintenance Type</label>
                                                    <select name="ic_maintenance_type" id="IC_MAINTENANCE_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Maintenance Type">
                                                           <option value="">-- Select Maintenance Type --</option>
                                                            <?php foreach ( $lst_maint_types as $key => $type_info ) { ?>
                                                                    <option value="{{ $type_info->mt_id }}">{{ $type_info->mt_type }}</option>
                                                            <?php  } ?>
                                                   </select>
						</div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                       <label class="control-label"> Date</label><br/>
                                                       <input type="text" name="ic_call_date"  id="IC_CALL_DATE" class="form-control" value="" />
                                                    </div>
                                               </div>
                                            <div class="col-md-4">
                                                    <label class="control-label">Archived Call</label>
                                                    <select name="ic_archived_call" id="IC_ARCHIVED_CALL"  class="form-control form-select" data-control="select2" data-placeholder="Select Archived Call">
                                                           <option value="">-- Select Archived Call --</option>
                                                            <option value="0">Pending</option>
                                                           <option value="1">Archived</option>
                                                   </select>
						</div>
                        <div class="col-md-4">
                            <label class="control-label">Result</label>
                            <select name="ic_result_id" id="IC_RESULT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Call Result">
                                <option value="0">-- Select Result --</option>
                                <?php foreach ( $lst_results as $key => $res_info ) { ?>
                                <option value="{{ $res_info->cr_id }}">{{ $res_info->cr_result_title }}</option>
                                <?php  } ?>
                            </select>
                        </div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 align-right">
					<a href="{{ url('/callcenter/inboundcall/addform') }}"
						class="btn btn-info"> <span> <i class="flaticon-grid-menu-v2"></i>
							<span> New Call </span>
					</span>
					</a>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
		<!--begin: Datatable -->
		<div class="table-responsive">
			<table id="tablPendingCalls" class="table table-striped gy-7 gs-7">
				<thead>
					<tr
						class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
						<th style="width: 2px;">#</th>
						<th style="width: 2px;">ID</th>
						<th>Date</th>
						<th>Time</th>
						<th>Client</th>
						<th>Region</th>
						<th>Area</th>
						<th>Address</th>
						<th>Phone</th>
						<th>Result</th>
						<th>Call Result</th>
						<th style="width: 2px;white-space: nowrap;">edit</th>
						<th style="width: 2px;white-space: nowrap;">Delete</th>
					</tr>
				</thead>
				<tbody class="LstInboundCalls" id="LstInboundCalls"></tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-10" align="left">
				<ul id="InboundCallsPagination" class="pagination-sm"></ul>
			</div>
			<div class="col-md-2" align="right"></div>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-xl-8 order-1 order-xl-1 align-right"></div>
			<div class="col-xl-2 order-2 order-xl-2 align-right">

			</div>
			<div class="col-xl-2 order-3 order-xl-3 align-right">
				<a href="{{ url('/callcenter/inboundcall/addform') }}"
					class="btn btn-info"> <span> <i class="flaticon-grid-menu-v2"></i>
						<span> New Call </span>
				</span>
				</a>
			</div>
		</div>

        <div class="row">
            <div class="col-md-12" style="text-align:right;height:700px;overflow: scroll">
                <div class="table-responsive">
                    <table id="TableMainCallResults" class="table table-striped gy-7 gs-7">
                        <thead>
                        <tr
                            class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>Date</th>
                            <th>Note</th>
                            <th>Result</th>
                            <th>Assign To</th>
                        </tr>
                        </thead>
                        <tbody class="LstMainCallResult" id="LstMainCallResult"></tbody>
                    </table>
                </div>
            </div>
        </div>
	</div>
</div>


<div class="modal fade" id="CallResultManagement" tabindex="-1" aria-labelledby="ModalCallResultManagement" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width:800px">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalEditBills">Manage Call Results</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form name="frm_save_results" id="FRM_SAVE_RESULTS">
              <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ic_call_ids" id="IC_CALL_IDS" value="0" />
                        <input type="hidden" name="cw_id" id="CW_ID" value="0" />
              </span>
                <div class="col-md-6">
                    <div class="form-group">
                       <label class="control-label"> Date</label><br/>
                       <input type="text" name="cw_creation_date"  id="CW_CREATION_DATE" class="form-control" value="{{ date('Y-m-d') }}" />
                    </div>
               </div>
                <div class="col-md-4">
                        <div class="form-group">
                          <label>Result </label>
                          <select name="cw_result_id" required="required" id="CW_RESULT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Result">
                                  <option value="">-- Select Result --</option>
                                  <?php foreach ( $lst_results as $key => $result_info ) { ?>
                                          <option value="<?php echo $result_info->cr_id;  ?>"><?php echo $result_info->cr_result_title;  ?></option>
                                  <?php  } ?>
                          </select>
                      </div>
                  </div>
               <div class="col-md-6">
                    <div class="form-group CallBack" style="display:none">
                       <label class="control-label"> Callback Date</label><br/>
                       <input type="text" name="cw_callback_date"  id="CW_CALLBACK_DATE" class="form-control" value="{{ date('Y-m-d') }}" />
                    </div>
               </div>
               <div class="col-md-6">
                   <div class="form-group">
                    <label class="control-label">Technician</label>
                    <select name="cw_assigned_to" id="CW_ASSIGNED_TO"  class="form-control form-select" data-control="select2" data-placeholder="Select Assigned To">
                           <option value="">-- Select Technician --</option>
                        @foreach ( $lst_admins as $key => $user_info )
                            <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                        @endforeach
                           @foreach ( $lst_technicians as $key => $user_info )
                                   <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                           @endforeach
                   </select>
                   </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                          <label class="control-label">Note</label>
                        <input type="text" name="cw_result_note"  id="CW_RESULT_NOTE" maxlength="500"  class="form-control" value="" />
                    </div>
                </div>
               <div class="col-md-12" style="text-align:right;padding-top:10px">
                   <button type="submit" name="btn_save_result" id="BTN_SAVE_RESULT" class="btn btn-info">Save changes</button>
                </div>
              <div class="col-md-12" style="text-align:right;height:700px;overflow: scroll">
                <div class="table-responsive">
                    <table id="TableCallResults" class="table table-striped gy-7 gs-7">
                        <thead>
                                <tr
                                        class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Date</th>
                                        <th>Note</th>
                                        <th>Result</th>
                                        <th>Assign To</th>
                                </tr>
                        </thead>
                        <tbody class="LstCallWResults" id="LstCallWResults"></tbody>
                    </table>
		</div>
               </div>
          </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="AddMainVoucher" tabindex="-1" aria-labelledby="ModalAddMainVoucher" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width:800px">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalEditBills">Add Maintenance Voucher</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form name="frm_save_voucher" id="FRM_SAVE_VOUCHER">
              <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ic_ids" value="0" />
                    <input type="hidden" name="products_stock"  value="" />
              </span>
              <div class="row">
                   <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label"> Date</label><br/>
                           <input type="text" name="ic_resolution_date"  required="required"  id="IC_RESOLUTION_DATE" class="form-control" value="" />
                        </div>
                   </div>
                   <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label"> MV Number </label><br/>
                           <input type="text" name="ic_doc_number"  required="required" id="IC_DOC_NUMBER" maxlength="25" class="form-control" value="" />
                        </div>
                   </div>
                   <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label"> Maintenance Number </label><br/>
                           <input type="text" name="ic_call_index"  required="required" id="IC_CALL_INDEX" maxlength="25" class="form-control" value="" />
                        </div>
                   </div>
                   <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label"> Comission </label><br/>
                           <input type="text" name="ic_comission"  required="required" id="IC_COMISSION" maxlength="25" class="form-control" value="0" />
                        </div>
                   </div>
                   <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label"> Visit Price </label><br/>
                           <input type="text" name="ic_visit_price"  required="required" id="IC_VISIT_PRICE" maxlength="25" class="form-control" value="0" />
                        </div>
                   </div>
                    <div class="col-md-4">
                       <div class="form-group">
                           <label> Currency <span class="required"> * </span></label><br/>
                           <select name="ic_currency_id" required="required" id="IC_CURRENCY_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Currency" style="width:100%">
                                   <option value="">-- Select Currency --</option>
                                   @foreach ( $lst_currencies as $key => $currency_info )
                                           <option {{ Session('company_currency') == $currency_info->cc_id ? "selected='selected'" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                   @endforeach
                           </select>
                       </div>
                   </div>
                   <div class="col-md-6 PaymentTypesDropdown" style="display:none">
                             <div class="form-group">
                                <label class="control-label">Payment Type </label><br/>
                                <select class="form-control form-select" id="IC_PAYMENT_TYPE" name="ic_payment_type" data-control="select2" data-placeholder="Select Payment Type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                  <div class="col-md-12">&nbsp;</div>
                  <div class="col-md-12">
                      <div class="row">
                          <div class="col-md-3">
                              <div class="form-group">
                                  <select class="form-control form-select" id="CP_PRODUCT_ID" name="cp_product_id" data-control="select2" data-placeholder="Select used items">
                                      <option value="0">-- Select used items --</option>
                                      @foreach($lst_products as $index => $product_info)
                                          <option value="{{ $product_info->p_id }}">{{ $product_info->p_barcode }}</option>
                                      @endforeach
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-3"><label></label><br/><label class="text-info ProductName"></label></div>
                          <div class="col-md-3"><input type="text" name="cp_quantity" class="form-control" value="1" /> </div>
                          <div class="col-md-3"><button type="button" name="btn_add_stock" class="btn btn-info" >Add Stock</button> </div>
                      </div>
                  </div>
                  <div class="col-md-12">&nbsp;</div>
                  <div class="col-md-12">
                      <div class="table-responsive">
                          <table class="table table-rounded table-striped border gy-7 gs-7">
                              <thead>
                              <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                  <th>Code</th>
                                  <th>Item</th>
                                  <th>Quantity</th>
                                  <th>Delete</th>
                              </tr>
                              </thead>
                              <tbody class="LstMaintenanceProducts">
                              </tbody>
                          </table>
                      </div>
                  </div>
                  <div class="col-md-12">&nbsp;</div>
                  <div class="col-md-12" style="text-align: right">
                      <button type="submit" name="btn_save_mv" id="BTN_SAVE_MV" class="btn btn-primary">Save changes</button>
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" name="btn_close" data-bs-dismiss="modal" >Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
