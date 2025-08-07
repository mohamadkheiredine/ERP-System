<?php
/***********************************************************
addbill
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
<script type="text/javascript" src="{{ url('js/modules/bills.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/savebill.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Bill Info</h3>
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

        <form name="frm_save_bills" id="FRM_SAVE_BILLS">
              <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ip_client_id" value="0" />
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
                          <label>Client Code </label>
                          <input type="text"  autocomplete="off" required="required"  name="ip_client_code" id="IP_CLIENT_CODE" class="form-control"  maxlength="25" value="0" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Client NAME </label>
                          <input type="text"  autocomplete="off" readonly="readonly"  name="ip_client_name" id="IP_CLIENT_NAME" class="form-control"  maxlength="25" value="" />
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
                          <label>Amount </label>
                          <input type="text"  autocomplete="off" required="required"  name="ip_payment_amount" id="IP_PAYMENT_AMOUNT" class="form-control"  maxlength="25" value="0" />
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
                                  <?php foreach ( $lst_admins as $key => $user_info ) { ?>
                                          <option  value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                  <?php  } ?>
                                  <?php foreach ( $lst_technicians as $key => $user_info ) { ?>
                                          <option  value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                  <?php  } ?>
                          </select>
                      </div>
                  </div>
                   <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Payment Type <span class="required"> * </span> </label><br/>
                           <select class="form-control" required="required" id="IP_PAYMENT_TYPE_ID" name="ip_payment_type_id" data-control="select2" data-placeholder="Select Payment Type">
                                           <option value="">-- Select Payment Type --</option>
                               @foreach($lst_payment_types as $index => $paytype_info)
                                 <option {{ $invoice_info->bi_payment_type == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>
                  <div class="col-md-6">
                        <div class="form-group">
                           <label class="control-label">Currency <span class="required"> * </span> </label><br/>
                           <select class="form-control" required="required" id="IP_CURRENCY_ID" name="ip_currency_id" data-control="select2" data-placeholder="Select Currency">
                                           <option value="">-- Select Currency --</option>
                               @foreach($lst_currency as $index => $currency_info)
                                 <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }}&nbsp;-&nbsp;{{ $currency_info->cc_currency_name }}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Payment Date </label>
                          <input type="text"  autocomplete="off" name="ip_pay_date" id="IP_PAY_DATE" class="form-control"  maxlength="50" value="" />
                      </div>
                  </div>
              </div>
                        <div class="row" style="height:5px;"></div>
              <div class="row">
                  <div class="col-md-9"></div>
                  <div class="col-md-3" align="right">
                       <button type="submit" name="btn_save_bills" id="BTN_SAVE_BILLS"  class="btn btn-info">Save</button>
                      <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                  </div>
              </div>
          </form>
    </div>
 </div>

@endsection
