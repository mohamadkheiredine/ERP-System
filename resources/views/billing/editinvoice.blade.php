<?php
/***********************************************************
editinvoice.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 10, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Billing Module"])

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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/invoices.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/receipts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/saveinvoices.js') }}"></script>

    <script>
        $(function(){
            $('#BI_PRODUCT').select2({
                dropdownParent: $('#InserItems'),
                placeholder: 'Select Item',
                allowClear: true
            });
        })

    </script>

@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Invoice Settings&nbsp;-&nbsp;{{ $invoice_info->bi_invoice_code }}&nbsp;-&nbsp;{!! $invoice_info->bi_invoice_status == 0 ? "<span class='m--font-info'>Draft</span>" : "<span class='m--font-primary'>Official</span>" !!}&nbsp;--&nbsp;{!! number_format($invoice_info->bi_total_price) !!}&nbsp;&nbsp;</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                    @if( $invoice_info->bi_invoice_status == 0 )
                        <li><a class="dropdown-item quickactions" data-action_type="CONVERT_TO_OFFICIAL" href="#">Convert to official Invoice</a></li>
                    @else
                      <li><a class="dropdown-item quickactions" data-action_type="RETURN_INVOICE" href="#">Return Invoice</a></li>
                        <li><a class="dropdown-item quickactions" data-action_type="REVERT_TO_DRAFT" href="#">Refert Back to draft Invoice</a></li>
                    @endif
                    <li><a class="dropdown-item quickactions" data-action_type="CREATE_RECEIPT" href="#">Create Receipt</a></li>
                    <li><a class="dropdown-item quickactions" data-action_type="PRINT_INVOICE" href="#">Print Invoice</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="{{ Config::get("appconfig.crm_telemarketing") == 1  ? "col-md-6" : "col-md-12" }} col-xs-12">
                <form name="frm_save_invoice" id="FORM_SAVE_INVOICE">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        <input type="hidden" name="bi_id" id="BI_ID" value="{{ $invoice_info->bi_id }}" />
                        <input type="hidden" name="bi_official_invoice"  value="{{ $invoice_info->bi_official_invoice }}" />
                        <input type="hidden" name="bi_invoice_code" id="BI_INVOICE_CODE" value="{{ $invoice_info->bi_invoice_code }}" />
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Invoice information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Invoice Date </label><br/>
                                <input type="text"  autocomplete="off" name="bi_invoice_date" id="BI_INVOICE_DATE" class="form-control"  maxlength="50" readonly="readonly"  value="{{ date('m/d/Y',strtotime($invoice_info->bi_invoice_date)) }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Invoice Ref </label><br/>
                                <input type="text" name="bi_invoice_ref" id="BI_INVOICE_REF" class="form-control"  maxlength="15"  value="{{ $invoice_info->bi_invoice_ref }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Client Code </label><br/>
                                <input type="text" autocomplete="off" name="bi_account_number" id="BI_ACCOUNT_NUMBER" class="form-control"  maxlength="50" value="{{  $invoice_info->bi_account_number }}" />
                            </div>
                        </div>
                        @if(Config::get("appconfig.crm_telemarketing") == 1)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Account <span class="required"> * </span></label><br/>
                                    <select id="INVOICE_ACCOUNT_ID" readonly name="invoice_account_id" class="form-control form-select" data-control="select2" data-placeholder="Select Account">
                                        <option value="0">-- Select Account --</option>
                                        @foreach($list_accounts as $index => $client_info)
                                            <option {{ $invoice_info->bi_client_id == $client_info->ca_id ? "selected" : "" }} value="{{ $client_info->ca_id }}">{{ $client_info->ca_account_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" id="INVOICE_ACCOUNT" name="invoice_account" value="{{ $invoice_info->bi_client_id }}" />
                            </div>
                        @else
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Customer <span class="required"> * </span></label><br/>
                                    <select class="bs-select form-control" id="FK_CUSTOMER_ID" readonly name="fk_customer_id">
                                        <option value="">-- Select Customer --</option>
                                        @foreach($list_customers as $index => $customer_info)
                                            <option {{ $invoice_info->fk_customer_id == $customer_info->ic_id ? "selected" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        @if(Config::get("appconfig.crm_telemarketing") == 0)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Bank Account </label><br/>
                                    <select class="bs-select form-control" required="required" id="FK_BANKACCOUNT_ID" name="fk_bankaccount_id">
                                        <option value="0">-- Select Account --</option>
                                        @foreach($lst_banks_info as $index => $bank_info)
                                            <option {{ $invoice_info->fk_bankaccount_id == $bank_info->ba_id ? "selected" : "" }}  value="{{ $bank_info->ba_id }}">{{ $bank_info->ba_account_label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Payment Terms</label><br/>
                                    <select class="bs-select form-control" id="FK_PAYMENT_TERMS" name="bi_payment_terms">
                                        <option value="0">-- Select Payment Terms --</option>
                                        @foreach($lst_payment_terms as $index => $payterms_info)
                                            <option {{ $invoice_info->bi_payment_terms == $payterms_info->pt_id ? "selected" : "" }} value="{{ $payterms_info->pt_id }}">{{ $payterms_info->pt_payment_terms }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Payment Type <span class="required"> * </span> </label><br/>
                                <select class="bs-select form-control" required="required" id="FK_PAYMENT_TYPE" name="bi_payment_type">
                                    <option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                        <option {{ $invoice_info->bi_payment_type == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tax Account</label>
                                <select class="bs-select form-control" id="BI_VAT_ID" name="bi_vat_id">
                                    <option value="0">-- Select Tax Account --</option>
                                    @foreach($lst_vat_accounts as $index => $vat_info)
                                        <option {{ $invoice_info->bi_vat_id == $vat_info->av_id ? "selected" : "" }} value="{{ $vat_info->av_id }}">{{ $vat_info->av_vat_label . "(" . $vat_info->av_vat_rate. "%)" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency <span class="required"> * </span></label><br/>
                                <select class="bs-select form-control" name="bi_invoice_currency" required="required" id="BI_INVOICE_CURRENCY" data-actions-box="true">
                                    <option value="">-- Select Currency --</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{ $invoice_info->bi_invoice_currency  == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                @if(Session("default_item") == 0)
                                <label> Item Type <span class="required"> * </span> </label><br/>
                                <select {{  $invoice_info->bi_invoice_status == 1 ? "disabled='disabled'" : "" }} class="bs-select form-control" name="bi_invoice_items_type" id="BI_INVOICE_ITEMS_TYPE" data-actions-box="true">
                                    <option value="">-- Select Type --</option>
                                    <option {{ $invoice_info->bi_invoice_type  == 1 ? "selected" : "" }} value="1">Products</option>
                                    <option {{ $invoice_info->bi_invoice_type  == 2 ? "selected" : "" }} value="2">Services</option>
                                    <option {{ $invoice_info->bi_invoice_type  == 3 ? "selected" : "" }}  value="3">Products & Services</option>
                                </select>
                                @else
                                    <label> Item Type <span class="required"> * </span> </label><br/>
                                    <input type="hidden" name="bi_invoice_items_type" value="{{ $invoice_info->bi_invoice_type }}" />

                                    <label>{{ $invoice_info->bi_invoice_type == 1 ? "Products" : ($invoice_info->bi_invoice_type == 2 ? "Services" : "Products & Services") }}</label>
                                @endif
                                <input type="hidden" name="ini_invoice_type" value="{{ $invoice_info->bi_invoice_type }}" />

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Invoice Discount </label><br/>
                                <input type="number"  autocomplete="off" min="0" max="100" step="1.0" name="bi_discount" id="BI_DISCOUNT" class="form-control"  maxlength="15"  value="{{  $invoice_info->bi_discount }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Second Currency</label><br/>
                                <select class="bs-select form-control" name="bi_second_currency" id="BI_SECOND_CURRENCY" data-actions-box="true">
                                    <option value="">-- Select Currency --</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{  $invoice_info->bi_second_currency == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br/>
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="bi_internal_invoice" id="BI_INTERNAL_INVOICE" {{ $invoice_info->bi_internal_invoice == 1 ? "checked" : "" }}  value="1"  />
                                    <span class="form-check-label fw-semibold text-muted">
                                          Internal Invoice
                                        </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 InternalCompanies" style="{{ $invoice_info->bi_internal_invoice == 1 ? "" : "display:none" }}">
                            <div class="form-group">
                                <label> Purchase Company</label><br/>
                                <select required="required"  name="bi_company_to" id="BI_COMPANY_TO" class="form-control form-select" data-control="select2" data-placeholder="Select Company To">
                                    <option value="0">-- Select Company --</option>
                                    @foreach ( $lst_companies as $key => $company_info )
                                        <option {{ $invoice_info->bi_company_to == $company_info->cd_id ? "selected" : "" }}  value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 SupplierDropdownHolder" style="{{ $invoice_info->bi_internal_invoice == 1 ? "" : "display:none" }}">
                            <div class="form-group">
                                <label class="control-label"> Supplier </label><br/>
                                <div class="SupplierDropdown"></div>
                                <input type="hidden" name="internal_supplier_target" value="{{ $invoice_info->bi_target_supplier }}" />
                            </div>
                        </div>
                        <div class="col-md-4 WarehouseDropdownHolder" style="{{ $invoice_info->bi_internal_invoice == 1 ? "" : "display:none" }}">
                            <div class="form-group">
                                <label class="control-label"> Target warehouse </label><br/>
                                <div class="WarehouseDropdown"></div>
                                <input type="hidden" name="internal_warehouse_target" value="{{ $invoice_info->bi_target_warehouse_id }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Exchange Rate </label><br/>
                                <input type="text"  autocomplete="off" name="bi_exchange_rate" id="BI_EXCHANGE_RATE" class="form-control"  maxlength="15"  value="{{  $invoice_info->bi_exchange_rate }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Invoice Notes</label><br/>
                                <textarea style="width:100%;height: 250px;" class="form-control" name="bi_invoice_note" id="BI_INVOICE_NOTE" >{{ $invoice_info->bi_invoice_note }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:15px;"></div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" align="right">
                            <button type="submit" name="btn_save_invoice" id="BTN_SAVE_INVOICE"  class="btn btn-info">Save</button>
                            <button type="submit" name="btn_save_new" id="BTN_SAVE_NEW"  class="btn btn-info">Save & New</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="row">
                    @if(Config::get("appconfig.crm_telemarketing") == 1)
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Insert Product</h3>
                                    <div class="card-toolbar">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form name="frm_link_product" id="FORM_LINK_PRODUCT">
                                        <div class="form-body">
                                     <span id="hidden_fields">
                                      <div class="form-group">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="bi_invoice_id" id="BI_invoice_ID" value="{{ $invoice_info->bi_id }}" />
                                         </div>
                                    </span>
                                            <div class="alert alert-success" style="display:none">
                                                <strong>Success!</strong> Product information is saved successfully!
                                            </div>
                                            <div class="alert alert-danger" style="display:none">
                                                <strong>Error!</strong> You have some form errors. Please check below.
                                            </div>
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"> Warehouse </label><br/>
                                                            <select   name="ii_warehouse_id" id="II_WAREHOUSE_ID"  style="width:100%" class="form-select" data-control="select2" data-placeholder="Select Warehouse">
                                                                <option value=""> -- Warehouse -- </option>
                                                                @foreach($lst_warehouses as $key => $warehouse_info)
                                                                    <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"> Products Name </label><br/>
                                                            <select   name="bi_product_id" id="BI_PRODUCT_ID"  style="width:100%" class="form-select" data-control="select2" data-placeholder="Select Product">
                                                                <option value=""> -- Product -- </option>
                                                                @foreach($lst_products as $key => $product_info)
                                                                    <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"> Products Code </label><br/>
                                                            <select   name="bi_product_code_id" id="BI_PRODUCT_CODE_ID"  style="width:100%" class="form-select" data-control="select2" data-placeholder="Select Product Code">
                                                                <option value=""> -- Product -- </option>
                                                                @foreach($lst_products as $key => $product_info)
                                                                    <option value="{{ $product_info->p_id }}">{{ $product_info->p_barcode }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 SerialNumberHolder">
                                                        <div class="form-group">
                                                            <label class="control-label"> Serial Number </label><br/>
                                                            <input type="text"  autocomplete="off" name="ii_product_serial_number" class="form-control" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"> Quanity </label><br/>
                                                            <input type="number"  autocomplete="off" name="bi_quanity" class="form-control" max="99999999" min="1" step="1" value="1" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label"> Price </label><br/>
                                                            <input type="text"  autocomplete="off" name="bi_item_price" class="form-control" value="1" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" style="padding-top:20px;">
                                                        <button id="BTN_reset" name="btn_reset" type="reset" class="btn btn-secondary">
                                                            Reset
                                                        </button>
                                                        <button type="submit" name="btn_link_item" id="BTN_LINK_ITEM" class="btn btn-primary">
                                                            Insert
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row" style="height:15px;"></div>
            </div>
        </div>
                <div class="form-body">

                    </div>

                    <div class="row" style="height:15px;"></div>



                   <div class="row">
                   		<div class="col-md-12">
                   			<ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tabProducts">{{ $invoice_info->bi_invoice_type == 1 ? "Products" : ($invoice_info->bi_invoice_type == 2 ? "Services" : "Products & Services") }}</a>
                                </li>
                                @if(Config::get("appconfig.crm_telemarketing") == 0)
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tabPayments">Bills</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tabReceipts">Receipts</a>
                                </li>
                                    @endif
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabProducts" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12" align="right">
                                            @if(Config::get("appconfig.crm_telemarketing") == 0)
                                                @if($invoice_info->bi_invoice_status == 0)
                                                    @if($invoice_info->bi_invoice_type == 1 || $invoice_info->bi_invoice_type == 3)
                                                        <button  type="button" name="btn_add_product" id="BTN_ADD_PRODUCT_TOP" class="btn btn-danger">Add Product</button>
                                                    @endif
                                                    @if($invoice_info->bi_invoice_type == 2 || $invoice_info->bi_invoice_type == 3)
                                                        <button type="button" name="btn_add_service" id="BTN_ADD_SERVICE_TOP" class="btn btn-danger">Add Service</button>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">&nbsp;</div>
                                    </div>
                                   <div class="row">
											<div class="col-md-12" id="LstProducts" align="center"></div>
										</div>
                                    <div class="row">
                                        <div class="col-md-12">&nbsp;</div>
                                    </div>
										<div class="row">
											<div class="col-md-12" align="right">
                                                @if(Config::get("appconfig.crm_telemarketing") == 0)
                                                    @if($invoice_info->bi_invoice_status == 0)
                                                        @if($invoice_info->bi_invoice_type == 1 || $invoice_info->bi_invoice_type == 3)
                                                            <button  type="button" name="btn_add_product" id="BTN_ADD_PRODUCT" class="btn btn-danger">Add Product</button>
                                                        @endif
                                                        @if($invoice_info->bi_invoice_type == 2 || $invoice_info->bi_invoice_type == 3)
                                                            <button type="button" name="btn_add_service" id="BTN_ADD_SERVICE" class="btn btn-danger">Add Service</button>
                                                        @endif
                                                    @endif
                                                    @endif
											</div>
										</div>
                                </div>
                                <div class="tab-pane fade" id="tabPayments" role="tabpanel">
                                   <div class="row">
                                            <div class="col-md-12" id="LstPaymentSplits"></div>
                                    </div>
                                    @if($invoice_info->bi_invoice_status == 0)
                                    <div class="row">
                                            <div class="col-md-11" align="right">
                                                     <button type="button" name="btn_create_rows" id="BTN_CREATE_ROWS" class="btn btn-info">Create Rows</button>

                                            </div>
                                            <div class="col-md-1" align="right">
                                                    <input type="number" name="number_payments" id="NUMBER_PAYMENT" min="0" max="50" value="0" step="1" class="form-control" />
                                            </div>
                                    </div>
                                    <div class="row" style="padding-top:10px;">
                                            <div class="col-md-12" align="right">
                                                    <button type="button" name="btn_save_rows" id="BTN_SAVE_ROWS" class="btn btn-focus">Save Rows</button>
                                            </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="tab-pane fade" id="tabReceipts" role="tabpanel">
                                   <div class="row">
											<div class="col-md-12" id="LstReceipts"></div>
										</div>
										@if($invoice_info->bi_invoice_status == 0)
										<div class="row">

											<div class="col-md-12" align="right">
												<button type="button" name="btn_generate_receipts" id="BTN_GENERATE_RECEIPTS" class="btn btn-danger">Generate Receipts</button>
											</div>
										</div>
										@endif
                                </div>
                            </div>
                   		</div>
                   </div>

                </div>

	</div>
<!-- Insert Product -->
<div class="modal fade" id="InserItems" tabindex="-1" role="dialog" aria-labelledby="InserItemsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					Insert Product
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_invoice_items" id="FRM_INVOICE_ITEMS" method="post"  enctype="multipart/form-data">
				    {!! csrf_field() !!}
				     <input type="hidden" name="invoice_id" value="{{ $invoice_info->bi_id }}" />
				     <input type="hidden" name="currency_id" value="{{ $invoice_info->bi_invoice_currency }}" />
				     <input type="hidden" name="invoice_type_item" id="INVOICE_TYPE_ITEM" value="{{ $invoice_info->bi_invoice_type }}" />
				     <input type="hidden" name="item_id" value="" />
				 	<div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Warehouse </label><br/>
                                <select   name="ii_warehouse_id" id="II_WAREHOUSE_ID"  style="width:100%" class="form-control form-select" data-control="select2" data-placeholder="Select Warehouse">
                                    <option value=""> -- Warehouse -- </option>
                                    @foreach($lst_warehouses as $key => $warehouse_info)
                                        <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> {{ $invoice_info->bi_invoice_type == 1 ? "Products" : "Services" }} </label><br/>
                                    <select   name="bi_product" id="BI_PRODUCT"  style="width:100%" class="form-control form-select" data-control="select2" data-placeholder="Select Product">
                                            <option value=""> -- Product -- </option>
                                            @foreach($lst_products as $key => $product_info)
                                                    <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_ref }}&nbsp;-&nbsp;{{ $product_info->p_product_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Serial Number </label><br/>
                                <input type="text"  autocomplete="off" name="ii_product_serial_number" class="form-control" value="" />
                            </div>
                        </div>
				 		<div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label"> Quanity </label><br/>
                                                         <input type="number"  autocomplete="off" name="bi_quanity" class="form-control" max="99999999" min="1" step="1" value="1" />
                                                    </div>
				 		</div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label"> Price </label><br/>
                                                         <input type="text"  autocomplete="off" name="bi_item_price" class="form-control" value="1" />
                                                    </div>
				 		</div>
                                            <div class="col-md-12" style="height:10px">&nbsp;</div>
				 		<div class="col-md-12">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="submit" name="btn_insert_item" id="BTN_INSERT_ITEM" class="btn btn-primary">
            					Insert
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="EditBills" tabindex="-1" aria-labelledby="ModalEditBills" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="width:800px">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalEditBills">Edit Bill</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form name="frm_save_bill" id="FRM_SAVE_BILL">
              <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ip_id" value="0" />
              </span>
              <div class="row">
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Receipt </label>
                           <input type="text"  autocomplete="off" required="required"  name="ip_billing_nbr" id="IP_BILLING_NBR" class="form-control"  maxlength="50" value="" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Date </label>
                           <input type="text"  autocomplete="off" name="ip_billing_date" id="IP_BILLING_DATE" class="form-control"  maxlength="50" value="" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Updated By </label>
                          <input type="text"  autocomplete="off" name="ip_updated_by" id="IP_UPDATED_BY" class="form-control" readonly="readonly"  maxlength="255" value="{{ session('user_fullname') }}" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Updated Date </label>
                          <input type="text"  autocomplete="off" name="ip_updated_date" id="IP_UPDATED_DATE" class="form-control" readonly="readonly"  maxlength="25" value="{{ date('Y-m-d') }}" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Doc Nbr </label>
                          <input type="text"  autocomplete="off" required="required"  name="ip_payment_doc" id="IP_PAYMENT_DOC" class="form-control"  maxlength="25" value="" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Collector  </label>
                              <select name="ip_collector_id" required="required" id="IP_COLLECTOR_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Collector">
                                  <option value="">-- Select Collector --</option>
                                  <?php foreach ( $lst_collectors as $key => $tech_info ) { ?>
                                          <option  value="<?php echo $tech_info->id;  ?>"><?php echo $tech_info->u_fullname;  ?></option>
                                  <?php  } ?>
                          </select>
                      </div>
                  </div>
                   <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label">Payment Type <span class="required"> * </span> </label><br/>
                                <select class="form-control" required="required" id="IP_PAYMENT_TYPE" name="ip_payment_type" data-control="select2" data-placeholder="Select Payment Type">
                        			<option value="">-- Select Payment Type --</option>
                                    @foreach($lst_payment_types as $index => $paytype_info)
                                      <option {{ $invoice_info->bi_payment_type == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                  <div class="col-md-12" style="text-align: right;height:10px;"></div>
                  <div class="col-md-12" style="text-align: right">
                      <button type="submit" id="BTN_SAVE_PAYMENT" name="btn_save_payment" class="btn btn-primary">Save changes</button>
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="InsertServices" tabindex="-1" role="dialog" aria-labelledby="InsertServicesModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InsertServicesModalLabel">
					Insert Service
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_invoice_services" id="FRM_INVOICE_SERVICES" method="post"  enctype="multipart/form-data">
				    {!! csrf_field() !!}
				     <input type="hidden" name="invoice_id" value="{{ $invoice_info->bi_id }}" />
				     <input type="hidden" name="invoice_type_item" id="INVOICE_TYPE_ITEM" value="{{ $invoice_info->bi_invoice_type }}" />
				     <input type="hidden" name="invoice_payment_type" id="INVOICE_PAYMENT_TYPE" value="{{ $invoice_info->bi_payment_type }}" />
				     <input type="hidden" name="item_id"  value="" />
				 	<div class="row">
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> {{ $invoice_info->bi_invoice_type == 1 ? "Products" : "Services" }} </label><br/>
                                     <select class="bs-select form-control" name="bi_service_id" id="BI_SERVICE_ID" required="required"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Services -- </option>
                                            @foreach($list_services as $key => $service_info)
                                                    <option data-validatept={{ $service_info->cs_validate_payment_type }} value="{{ $service_info->cs_id }}">{{ $service_info->cs_service_title }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label">Supplier</label><br/>
                                     <select class="bs-select form-control" name="ii_supplier_id" id="II_SUPPLIER_ID"  style="width:100%" data-actions-box="true">
                                            <option value="0"> -- Supplier -- </option>
                                            @foreach( $lst_suppliers as $key => $supp_info )
                                                    <option value="{{ $supp_info->ss_id }}">{{ $supp_info->ss_supplier_name }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group PaymentType" style="display: none">
                                    <label class="control-label">Payment Type</label><br/>
                                     <select class="bs-select form-control" name="ii_payment_type" id="II_PAYMENT_TYPE" style="width:100%" data-actions-box="true">
                                            <option value=""> -- Payment Type -- </option>
                                            @foreach( $lst_payment_types as $key => $pt_info )
                                                    <option value="{{ $pt_info->pt_id }}">{{ $pt_info->pt_payment_type }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			<label class="control-label">Service Cost Price</label></br>
				 			<input type="text"  autocomplete="off" class="form-control" name="ii_cost_price" id="II_COST_PRICE"  required="required" value="" />
				 		</div>
				 		<div class="col-md-12">
				 			<label class="control-label">Service Price</label></br>
				 			<input type="text"  autocomplete="off" class="form-control" name="bi_service_price" id="PI_SERVICE_PRICE"  required="required" value="" />
				 		</div>
				 		<div class="col-md-12">
				 			<label class="control-label">Service Currency</label></br>
				 			<span><b>{{ $currencies_array[$invoice_info->bi_invoice_currency]['cc_currency_code'] }}</b></span>
				 		</div>
				 		<div class="col-md-12" align="right">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="submit" name="btn_insert_service" id="BTN_INSERT_SERVICE" class="btn btn-primary">
            					Insert
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
    </div>


<!-- End Insert Service -->
@endsection
