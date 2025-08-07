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
<script type="text/javascript" src="{{ url('js/modules/bills.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/billsmanagement.js') }}"></script>
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
              <ul class="dropdown-menu"> </ul>
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
									<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" tabindex="1" />
								</div>
								<!--end::Input group-->
							</div>
						</div>
						<div class="col-md-4">
                        <div class="form-group">
                           <label class="form-label"> From Date </label><br/>
                           <input type="text" name="pi_start_date" id="PI_START_DATE" value="" class="form-control" />
                       </div>
                                           </div>
                        <div class="col-md-4">
                             <div class="form-group">
                           <label class="form-label"> To Date </label><br/>
                           <input type="text" name="pi_end_date" id="PI_END_DATE" value="" class="form-control" />
                       </div>
						</div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label"> Up To Date </label><br/>
                                <input type="text" name="pi_upto_date" id="PI_UPTO_DATE" value="" class="form-control" />
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
    			<table class="table table-striped">
            		<thead>
            			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            				<th style="width:2px;white-space: nowrap;" title="#">#</th>
            				<th title="Voucher Ref"> Ref </th>
                            <th title="Bill Nbr"> Bill Nbr </th>
            				<th title="Voucher Date"> Date </th>
            				<th title="Client Code"> Client Code </th>
            				<th title="Client Name"> Client Name </th>
            				<th title="Total Price"> Total Price </th>
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
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="BillsPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
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
@endsection
