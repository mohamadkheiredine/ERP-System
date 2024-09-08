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
@extends('layouts.layout',['page_title' => "Deals Management > Add New Deal"])

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
        <h3 class="card-title">Add Deal</h3>
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
                                               <strong>Success!</strong> Account Deal Information is saved successfully!
                                            </div>
                                            <div class="alert alert-danger" style="display:none">
                                                <strong>Error!</strong> You have some form errors. Please check below.
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Deal Code <span class="required"> * </span></label>
                                                            <input type="text" name="ad_deal_code" id="AD_DEAL_CODE" class="form-control" required="required" maxlength="15"  value="" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Title <span class="required"> * </span></label>
                                                        <input type="text" name="ad_deal_title" id="AD_DEAL_TITLE" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Deal Owner </label>
                                                            <select name="ad_deal_owner" id="AD_DEAL_OWNER" class="form-control form-select" data-control="select2" data-placeholder="Select Deal Owner">
                                                                <option value="0"> Owner </option>
                                                                @foreach ($lst_users as $key => $user_info )
                                                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Lead </label>
                                                        <select name="fk_lead_id" id="FK_LEAD_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Deal Lead">
                                                                <option value="0"> Select Lead </option>
                                                                @foreach ($lst_leads as $key => $lead_info )
                                                                        <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name  }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Contact </label>
                                                        <select name="fk_contact_id" id="FK_CONTACT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Deal Main Contact">
                                                                <option value="0"> Select Contact </option>
                                                                @foreach ($lst_contacts as $key => $cc_info )
                                                                        <option value="{{ $cc_info->cc_id }}">{{ $cc_info->cc_first_name . " " . $cc_info->cc_last_name  }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Account </label>
                                                        <select name="fk_account_id" id="FK_ACCOUNT_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Deal Related Account">
                                                                <option value="0"> Account </option>
                                                                @foreach ($lst_accounts as $key => $account_info )
                                                                        <option value="{{ $account_info->ca_id }}">{{ $account_info->ca_account_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Amount <span class="required"> * </span></label>
                                                        <input type="text" name="ad_deal_amount" id="AD_DEAL_AMOUNT" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Down Payment <span class="required"> * </span></label>
                                                        <input type="text" name="ad_down_payment" id="AD_DOWN_PAYMENT" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Number of Payments <span class="required"> * </span></label>
                                                        <input type="text" name="ad_nbr_of_payments" id="AD_NBR_OF_PAYMENT" class="form-control" required="required" maxlength="255"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Currency </label>
                                                            <select name="ad_currency_id" id="AD_CURRENCY_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Currency">
                                                                <option value="0"> Select Currency </option>
                                                                @foreach ($lst_currencies as $key => $currency_info )
                                                                        <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }}&nbsp;-&nbsp;{{ $currency_info->cc_currency_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Close Date <span class="required"> * </span></label>
                                                        <input type="text" name="ad_closing_date" id="AD_CLOSING_DATE" class="form-control" required="required" maxlength="15" readonly="readonly"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Deal Stage </label>
                                                        <select name="ad_deal_stage" id="AD_DEAL_STAGE"  class="form-control form-select" data-control="select2" data-placeholder="Select Deal Stage">
                                                                <option value="0"> Deal Stage </option>
                                                                @foreach ($lst_deal_stages as $key => $ds_info )
                                                                        <option value="{{ $ds_info->cs_id }}">{{ $ds_info->cs_stage_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Sales  </label>
                                                            <select name="fk_sales_id" id="FK_SALES_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Sales">
                                                                <option value="0"> Select Salesman </option>
                                                                @foreach ($lst_user_sales as $key => $user_info )
                                                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Telemarketer </label>
                                                            <select name="fk_telemarketing_id" id="FK_TELEMARKETING_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                                                <option value="0"> Select Telemarketer </option>
                                                                @foreach ($lst_user_telemarketing as $key => $user_info )
                                                                        <option value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Deal type </label>
                                                        <select name="ad_deal_type" id="AD_DEAL_TYPE"  class="form-control form-select" data-control="select2" data-placeholder="Select Deal Type">
                                                                <option value="0"> Deal Type </option>
                                                                <option value="1"> Existing Business </option>
                                                                <option value="2"> New Business  </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                    <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Probability (%)</label>
                                                        <input type="text" name="ad_deal_probability" id="AD_DEAL_PROBABILITY" class="form-control"  maxlength="5"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Expected Revenue</label>
                                                        <input type="text" name="ad_expected_revenue" id="AD_EXPECTED_REVENUE" class="form-control"  maxlength="50"  value="" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                      <br/>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                          <input class="form-check-input" type="checkbox" name="ad_is_approved" id="AD_IS_APPROVED"  value="1"  />
                                                          <span class="form-check-label fw-semibold text-muted">
                                                            Deal Approved
                                                          </span>
                                                      </label> 
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Deal Next Step </label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="AD_NEXT_STEP"  class="form-control" name="ad_next_step"  cols=""></textarea>
                                                     </div>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Deal Description</label><br/>
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
                                                    </div>
                                               </div>
                                               <div class="col-md-12"></div>
                                               <div class="col-md-12" align="right">
                                                   <button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT"  class="btn btn-success">Add Product</button>
                                               </div>
                                           </div>
                                           <div class="row" style="height:5px;"></div>
                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3" align="right">
                                                     <button type="submit" name="btn_save_deal" id="BTN_SAVE_DEALS"  class="btn btn-info">Save</button>
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
                                    Products Deal
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