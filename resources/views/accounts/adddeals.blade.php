<?php
/***********************************************************
adddeals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Contracts Management > Add New Contract"])

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
<script type="text/javascript" src="{{ url('js/libraries/crm/savedeals.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add Contract</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <form name="frm_save_deals" id="FORM_SAVE_DEALS">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="deals" value="" />
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                               <strong>Success!</strong> Account Contract Information is saved successfully!
                                            </div>
                                            <div class="alert alert-danger" style="display:none">
                                                <strong>Error!</strong> You have some form errors. Please check below.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label">Date <span class="required"> * </span></label>
                                                            <input type="text" name="ad_deal_date" id="AD_DEAL_DATE" class="form-control" required="required" maxlength="15"  value="{{ date('Y-m-d') }}" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label">Client Code <span class="required"> * </span></label>
                                                            <input type="text" name="ad_account_code" id="AD_ACCOUNT_CODE" class="form-control" required="required" maxlength="15"  value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label">Contract Code <span class="required"> * </span></label>
                                                            <input type="text" name="ad_deal_code" id="AD_DEAL_CODE" class="form-control" required="required" maxlength="15"  value="" />
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
                                                        <label class="control-label">Client Name</label>
                                                        <input type="text" name="ad_client_name" id="AD_CLIENT_NAME" class="form-control" readonly="readonly" maxlength="255"  value="" />
                                                    </div>
                                                    <input type="hidden" name="fk_account_id" id="FK_ACCOUNT_ID" value="0" />
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Amount <span class="required"> * </span></label>
                                                        <input type="text" name="ad_deal_amount" id="AD_DEAL_AMOUNT" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Payment Type </label>
                                                        <select name="ad_contract_type" id="AD_CONTRACT_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Contract Type">
                                                            <option value="0"> Select Contract Type </option>
                                                            <option value="1"> Full Payment</option>
                                                            <option value="2"> Installment</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4 DownPaymentHolder" >
                                                     <div class="form-group">
                                                        <label class="control-label">Down Payment</label>
                                                        <input type="text" name="ad_down_payment" id="AD_DOWN_PAYMENT" class="form-control"  maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                 <div class="col-md-4 NumberofPaymentHolder">
                                                     <div class="form-group">
                                                        <label class="control-label">Number of Payments <span class="required"> * </span></label>
                                                        <input type="text" name="ad_nbr_of_payments" id="AD_NBR_OF_PAYMENT" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 RemainingPaymentHolder">
                                                     <div class="form-group">
                                                        <label class="control-label">Remaining Payment</label>
                                                        <input type="text" name="ad_remaining_payment" id="AD_REMAINING_PAYMENT" class="form-control" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Currency </label>
                                                            <select name="ad_currency_id" id="AD_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                                                <option value="0"> Select Currency </option>
                                                                @foreach ($lst_currencies as $key => $currency_info )
                                                                        <option {{ $currency_info->cc_id == session('company_currency') ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }}&nbsp;-&nbsp;{{ $currency_info->cc_currency_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label LabelBill">First Bill Date <span class="required"> * </span></label>
                                                        <input type="text" name="ad_first_bill_date" id="AD_FIRST_BILL_DATE" class="form-control" required="required" maxlength="15" readonly="readonly"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-2"></div>
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                 <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> Salesman  </label>
                                                                            <select name="fk_sales_id" id="FK_SALES_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Sales">
                                                                                <option value="0"> Select Salesman </option>

                                                                                @foreach ($lst_admins as $key => $user_info )
                                                                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                                @endforeach
                                                                                @foreach ($lst_user_sales as $key => $user_info )
                                                                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                                @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                     <div class="form-group">
                                                                        <label> Salesman Comm. </label>
                                                                        <input type="text" name="ad_sales_comm" class="form-control" value="" />
                                                                     </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> Telemarketer </label>
                                                                            <select name="fk_telemarketing_id" id="FK_TELEMARKETING_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                                                                <option value="0"> Select Telemarketer </option>

                                                                                @foreach ($lst_admins as $key => $user_info )
                                                                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                                @endforeach
                                                                                @foreach ($lst_user_telemarketing as $key => $user_info )
                                                                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                                @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                     <div class="form-group">
                                                                        <label> Telemarketer Comm. </label>
                                                                        <input type="text" name="ad_telemarketing_comm" class="form-control" value="" />
                                                                     </div>
                                                                </div>
                                                                 <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> Supervisor </label>
                                                                            <select name="fk_supervisor_id" id="FK_SUPERVISOR_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Supervisor">
                                                                                <option value="0"> Select Supervisor </option>

                                                                                @foreach ($lst_admins as $key => $user_info )
                                                                                    <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                                @endforeach
                                                                                @foreach ($lst_supervisors as $key => $user_info )
                                                                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                                @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                     <div class="form-group">
                                                                        <label> Supervisor Comm. </label>
                                                                        <input type="text" name="ad_supervisor_comm" class="form-control" value="" />
                                                                     </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> Technician </label>
                                                                        <select name="fk_technician_id" id="FK_TECHNICIAN_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Technician">
                                                                            <option value="0"> Select Technician </option>
                                                                            @foreach ($lst_admins as $key => $user_info )
                                                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                            @endforeach
                                                                            @foreach ($lst_technicians as $key => $user_info )
                                                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> Technician Comm. </label>
                                                                        <input type="text" name="ad_technician_comm" class="form-control" value="" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> General Manager </label>
                                                                        <select name="fk_manager_id" id="FK_MANAGER_ID" class="form-control form-select" data-control="select2" data-placeholder="Select General Manager">
                                                                            <option value="0"> Select Manager </option>
                                                                            @foreach ($lst_admins as $key => $user_info )
                                                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                            @endforeach
                                                                            @foreach ($lst_general_managers as $key => $user_info )
                                                                                <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label> Manager Comm. </label>
                                                                        <input type="text" name="ad_manager_comm" class="form-control" value="" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="col-md-12 BillsCom">

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label> S/N </label>
                                                                    <input type="text" name="ad_serial_number" class="form-control" maxlength="255" value="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label> Warranty Start Date </label>
                                                                    <input type="text" name="ad_warranty_date" id="AD_WARRANTY_DATE" class="form-control" maxlength="255" value="" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <br/>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                          <input class="form-check-input" type="checkbox" name="ad_is_approved" id="AD_IS_APPROVED"  value="1"  />
                                                          <span class="form-check-label fw-semibold text-muted">
                                                            Contract Approved
                                                          </span>
                                                      </label>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Contract Next Step </label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="AD_NEXT_STEP"  class="form-control" name="ad_next_step"  cols=""></textarea>
                                                     </div>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Contract Description</label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="AD_DEAL_DESCRIPTION"  class="form-control" name="ad_deal_description"  cols=""></textarea>
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
                                                                    <button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT"  class="btn btn-success">Add Product</button>
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
                                                                    <button type="button" name="btn_generate_payments" id="BTN_GENERATE_PAYMENTS"  class="btn btn-success">Generate Payment</button>
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
                                                     <button type="submit" name="btn_save_deal" id="BTN_SAVE_DEALS"  class="btn btn-info">Save</button>
                                                     <button type="submit" name="btn_save_continue_deal" id="BTN_SAVE_CONTINUE_DEALS"  class="btn btn-success">Save & Continue</button>
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
    </div>
</div>

<div class="modal fade" id="ProductsDealModel" tabindex="-1" role="dialog" aria-labelledby="ProductsDealModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="ProductsDealModelLabel">
                                    Products Contract
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">
                                                &times;
                                        </span>
                                </button>
                        </div>
                        <div class="modal-body">
                                <form name="frm_product_deals" id="FRM_PRODUCT_DEALS">
                                        <span id="hidden_field">
                                                  {!! csrf_field() !!}
                                        </span>
                                        <div class="row">
                                                <div class="col-12">
                                                <div class="form-group">
                                                            <label class="control-label">Product Deals <span class="required"> * </span></label><br/>
                                                        <select name="p_product_deal" id="P_PRODUCT_DEAL"  class="form-control form-select" data-control="select2" data-placeholder="Select Product Deal">
                                                                <option value="0"> Select Product </option>
                                                                  @foreach ($lst_products as $key => $product_info )
                                                                        <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
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
                                <button type="button" name="btn_assign_product_deal" class="btn btn-primary">
                                        Submit
                                </button>
                        </div>
                </div>
        </div>
</div>
<!-- End Models Section -->

@endsection
