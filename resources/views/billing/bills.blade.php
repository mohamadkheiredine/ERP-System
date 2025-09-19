<?php
/***********************************************************
bills
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 22, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Billing Management"])

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
<script type="text/javascript" src="{{ url('default/assets/plugins/tablesorter/dist/js/jquery.tablesorter.js') }}"></script>
<script type="text/javascript" src="{{ url('default/assets/plugins/tablesorter/dist/js/jquery.tablesorter.widgets.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/bills.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/billsmanagement.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.querySelector('#tableBillsManagement');
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
        <h3 class="card-title">Bills Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" data-action_type="DOWNLOAD_EXCEL_REPORT" href="#">Download Excel Report</a></li>
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
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
							<div class="d-flex align-items-center">
								<!--begin::Input group-->
								<div class="position-relative w-md-400px me-md-2">
									<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<input type="text" autocomplete="off" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" tabindex="1" />
								</div>
								<!--end::Input group-->
							</div>
						</div>
						<div class="col-md-4">
                        <div class="form-group">
                           <label class="form-label"> From Date </label><br/>
                           <input type="text" autocomplete="off" name="pi_start_date" id="PI_START_DATE" value="" class="form-control" />
                       </div>
                                           </div>
                        <div class="col-md-4">
                             <div class="form-group">
                           <label class="form-label"> To Date </label><br/>
                           <input type="text" autocomplete="off" name="pi_end_date" id="PI_END_DATE" value="" class="form-control" />
                       </div>
						</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"> Up To Date </label><br/>
                                <input type="text" autocomplete="off" name="pi_upto_date" id="PI_UPTO_DATE" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Area <span class="required"> * </span> </label>
                                <select name="bill_area" required="required" id="BILL_AREA"  tabindex="5"  class="form-control form-select" data-control="select2" data-placeholder="Select Area">
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
                                    <select name="bill_region" required="required"  id="BILL_REGION" class="form-control form-select" tabindex="3" data-control="select2" data-placeholder="Select Region">
                                        <option value="0">-- Select Region --</option>
                                        @foreach( $lst_regions as $key => $region_info )
                                            <option value="{{ $region_info->lr_region }}">{{ $region_info->lr_region }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"> Bill Status </label><br/>
                                <select name="ip_payment_status" id="IP_PAYMENT_STATUS" class="form-control form-select" data-control="select2" data-placeholder="Select Payment Status" >
                                    <option  value="-"> -- Select Status -- </option>
                                    <option  value="0">Not Paid</option>
                                    <option  value="1">Partial Paid</option>
                                    <option  value="2">Paid</option>
                                </select>
                            </div>
                        </div>
					</div>
				</div>
				<div class="col-xl-4">

				</div>
			</div>
		</div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="row">
            <div class="col-md-12" align="right">
                    <a href="{{ url('billing/bills/addform') }}" class="btn btn-info">
                            <span>
                                    <i class="fa fa-money"></i>
                                    <span>
                                            New Bill
                                    </span>
                            </span>
                    </a>
            </div>
        </div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="row">
    		<div class="col-md-12 table-responsive">
    			<table class="table table-striped tablesorter" id="tableBillsManagement">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            				<th style="width:2px;white-space: nowrap;" title="#">#</th>
            				<th title="Voucher Ref" data-sort="string"> Ref </th>
                            <th title="Bill Nbr" data-sort="string"> Bill Nbr </th>
            				<th title="Voucher Date" data-sort="date"> Date </th>
            				<th title="Client Code" data-sort="string"> Client Code </th>
            				<th title="Client Name" data-sort="string"> Client Name </th>
            				<th title="Region" data-sort="string"> Region </th>
            				<th title="Area" data-sort="string"> Area </th>
            				<th title="Phone" data-sort="number"> Phone </th>
            				<th title="Full Address" data-sort="string"> Full Address </th>
            				<th title="Bill Amount" data-sort="number"> Bill Amount </th>
            				<th title="Remaining" data-sort="number"> Remaining </th>
            				<th title="Bill Status" data-sort="string"> Bill Status </th>
            				<th title="Result" data-sort="string"> Result </th>
            				<th title="Notes" data-sort="string"> Notes </th>
            				<th style="width:2px;" nowrap title="#"> edit </th>
            				<th style="width:2px;" nowrap title="#"> Delete </th>
            			</tr>
            		</thead>
            		<tbody id="LstBills">
            		</tbody>
            	</table>
    		</div>
		</div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="row"><div class="col-md-12"><span class="text-primary">Total Amount:</span>&nbsp;<span class="text-primary" id="total_amount">-</span>&nbsp;<b class="text-primary">USD</b></div></div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="BillsPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="row">
            <div class="col-md-12" style="text-align:right;height:700px;overflow: scroll">
                <div class="table-responsive">
                    <table id="TableBillsCallResults" class="table table-striped gy-7 gs-7">
                        <thead>
                        <tr
                            class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>Date</th>
                            <th>Note</th>
                            <th>Result</th>
                            <th>Assign To</th>
                        </tr>
                        </thead>
                        <tbody class="LstBillsCallResult" id="LstBillsCallResult"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <div class="row">
            <div class="col-md-12" align="right">
                    <a href="{{ url('billing/bills/addform') }}" class="btn btn-info">
                            <span>
                                    <i class="fa fa-money"></i>
                                    <span>
                                            New Bill
                                    </span>
                            </span>
                    </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="BillResultManagement" tabindex="-1" aria-labelledby="ModalBillResultManagement" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:800px">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditBills">Manage Nill Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form name="frm_save_results" id="FRM_SAVE_RESULTS">
              <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ip_bill_id" id="IP_BILL_ID" value="0" />
                        <input type="hidden" name="cw_id" id="CW_ID" value="0" />
              </span>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Date</label><br/>
                            <input type="text" name="bw_creation_date"  id="BW_CREATION_DATE" class="form-control" value="{{ date('Y-m-d') }}" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Result </label>
                            <select name="bw_result_id" required="required" id="BW_RESULT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Result">
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
                            <input type="text" name="bw_callback_date"  id="BW_CALLBACK_DATE" class="form-control" value="{{ date('Y-m-d') }}" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Technician</label>
                            <select name="bw_assigned_to" id="BW_ASSIGNED_TO"  class="form-control form-select" data-control="select2" data-placeholder="Select Assigned To">
                                <option value="">-- Select Technician --</option>
                                @foreach ( $lst_admins as $key => $user_info )
                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                                @foreach ( $lst_collecters as $key => $user_info )
                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Note</label>
                            <input type="text" name="bw_result_note"  id="BW_RESULT_NOTE" maxlength="500"  class="form-control" value="" />
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

@endsection
