<?php
/***********************************************************
 * viewdeal.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/20/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/
?>

@extends('layouts.layout',['page_title' => "Contracts Management > View Contract"])

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
    <script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ url('js/modules/deals.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/crm/viewdeal.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Existing Contract</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item DownloadContract" data-action_type="DOWNLOAD_CONTRACT" href="#">Download Contract</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form name="frm_save_deals" id="FORM_SAVE_DEALS">
                <div class="form-body">
                                             <span id="hidden_fields">
                                              {!! csrf_field() !!}
                                              <input type="hidden" name="ad_id" value="{{ $deal_info->ad_id }}" />
                                            </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Account Contract Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Date <span class="required"> * </span></label><br/>
                                <span class="text-info">{{ $deal_info->ad_deal_date }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Client Code <span class="required"> * </span></label><br/>
                                <span class="text-info">{{ $deal_info->ad_account_code }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Contract Code <span class="required"> * </span></label><br/>
                                <span class="text-info">{{ $deal_info->ad_deal_code }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Contract Type <span class="required"> * </span></label>
                                <span id="ad_deal_types" class="control-label"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Amount <span class="required"> * </span></label><br/>
                                <span class="text-info">{{ $deal_info->ad_deal_amount }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Payment Type </label><br/>
                                <span class="text-info">{{ $deal_info->ad_contract_type == 1 ? "Full Payment" : "Installment" }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 DownPaymentHolder">
                            <div class="form-group">
                                <label class="control-label"> Down Payment</label><br/>
                                <span class="text-info">{{ $deal_info->ad_down_payment  }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 NumberofPaymentHolder">
                            <div class="form-group">
                                <label class="control-label">Remaining Payment</label><br/>
                                <span class="text-info">{{ $deal_info->ad_remaining_payment  }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 RemainingPaymentHolder">
                            <div class="form-group">
                                <label class="control-label"> Number of Payments <span class="required"> * </span></label><br/>
                                <span class="text-info">{{ $deal_info->ad_nbr_of_payments  }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency </label><br/>
                                <span class="text-info">{{ $deal_info->Currency ? $deal_info->Currency->cc_currency_code : "USD"  }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label LabelBill">First Bill Date <span class="required"> * </span></label><br/>
                                <span class="text-info">{{ $deal_info->ad_first_bill_date  }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Salesman  </label><br/>
                                                <span class="text-info">{{ $deal_info->Salesman->u_fullname  }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Salesman Comm. </label><br/>
                                                <span class="text-info">{{ $deal_info->ad_sales_comm  }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Telemarketer </label><br/>
                                                <span class="text-info">{{ $deal_info->Telemarketing->u_fullname   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Telemarketer Comm. </label><br/>
                                                <span class="text-info">{{ $deal_info->ad_telemarketing_comm   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Supervisor </label><br/>
                                                <span class="text-info">{{ $deal_info->Supervisor->u_fullname   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Supervisor Comm. </label><br/>
                                                <span class="text-info">{{ $deal_info->ad_supervisor_comm   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Technician </label><br/>
                                                <span class="text-info">{{ $deal_info->Technician->u_fullname   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Technician Comm. </label><br/>
                                                <span class="text-info">{{ $deal_info->ad_technician_comm   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> General Manager </label><br/>
                                                <span class="text-info">{{ $deal_info->Manager->u_fullname   }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Manager Comm. </label><br/>
                                                <span class="text-info">{{ $deal_info->ad_manager_comm   }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label> S/N </label><br/>
                                                    <span class="text-info">{{ $deal_info->ad_serial_number }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label> Warranty Start Date </label><br/>
                                                    <span class="text-info">{{ $deal_info->ad_warranty_date }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-12 BillsCom">

                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-4">
                            <br/>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <span class="text-primary">
                                                            Contract Approved
                                                          </span>
                            </label>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Next Step </label><br/>
                                <div class="text-light-info bg-info">{{ $deal_info->ad_next_step }}</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Description</label><br/>
                                <div class="text-light-info bg-info">{{ $deal_info->ad_deal_description }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_products">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_payments">Payments Statement</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="kt_tab_products" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="LstProductsMain">
                                                <table class="table table-striped gy-7 gs-7">
                                                    <thead>
                                                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <th title="#">#</th>
                                                        <th title="Id"> ID </th>
                                                        <th title="Reference"> Product Reference </th>
                                                        <th title="Name"> Product Name  </th>
                                                        <th title="Price"> Product Price </th>
                                                        <th title="delete"> Delete </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody  id="LstProducts" ></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="height:10px;"></div>
                                        <div class="col-md-12" align="right">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="kt_tab_payments" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="LstPaymentsMain">
                                                <table class="table table-striped gy-7 gs-7">
                                                    <thead>
                                                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <th title="Bill#"> Bill# </th>
                                                        <th title="Value Date"> Value Date </th>
                                                        <th title="Bill Status"> Bill Status </th>
                                                        <th title="Bill Amount"> Bill Amount </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody  id="LstPaymentStatments" ></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="height:10px;"></div>
                                        <div class="col-md-12" align="right">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

