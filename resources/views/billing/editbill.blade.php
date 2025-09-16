<?php
/***********************************************************
editbill
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

    <form name="frm_save_bills" id="FRM_SAVE_BILLS">
    <div class="card-header">
        <h3 class="card-title">Edit Bill Info</h3>
        <div class="col-md-3">
            <div class="form-group">
                <label>Date : </label><br/>
                <span class="text-success">{{ $bill_info->ip_billing_date }}</span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Client Code : </label><br/>
                <span class="text-success" >{{ $bill_info->ip_client_code }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Client NAME : </label><br/>
                <span class="text-success">{{ $bill_info->ip_client_name }}</span>
            </div>
        </div>
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
              <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="ip_id" value="{{ $bill_info->ip_id }}" />
                        <input type="hidden" name="ip_paid_amount" value="{{ $bill_info->ip_paid_amount }}" />
                        <input type="hidden" name="ip_remaining_amount" value="{{ $bill_info->ip_remaining_amount }}" />
                        <input type="hidden" name="ip_initial_amount" value="{{ $bill_info->ip_payment_amount }}" />
                        <input type="hidden" name="ip_extra_amount" value="0" />
              </span>
              <div class="row">
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Receipt </label>
                           <input type="text"  autocomplete="off" required="required"  name="ip_billing_nbr" id="IP_BILLING_NBR" class="form-control"  maxlength="50" value="{{ $bill_info->ip_billing_nbr }}" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Doc Nbr </label>
                          <input type="text" {{  (CheckPrivilage('erp_ability_to_change_bill_fields') == "allow") ? "" : "readonly='readonly'" }}  autocomplete="off" required="required"  name="ip_payment_doc" id="IP_PAYMENT_DOC" class="form-control"  maxlength="25" value="{{ $bill_info->ip_payment_doc }}" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label>Payment Date </label>
                          <input type="text"  autocomplete="off" name="ip_pay_date" id="IP_PAY_DATE" class="form-control"  maxlength="50" value="{{ $bill_info->ip_pay_date }}" />
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Amount </label>
                          <input type="text"  {{  (CheckPrivilage('erp_ability_to_change_bill_fields') == "allow") ? "" : "readonly='readonly'" }}   autocomplete="off" required="required"  name="ip_payment_amount" id="IP_PAYMENT_AMOUNT" class="form-control"  maxlength="25" value="0" />
                      </div>
                  </div>
                  <div class="col-md-6" style="height:50px;vertical-align: middle;margin-top: 20px;">
                      <div class="form-group">
                          <label>Paid Amount </label><br/>
                          <span class="text-success PaidAmount">{{ $bill_info->ip_paid_amount }}</span>
                      </div>
                  </div>
                  <div class="col-md-6" style="height:50px;vertical-align: middle;margin-top: 20px;">
                      <div class="form-group">
                          <label>Remaining Amount </label><br/>
                          <span class="text-success RemainingAmount">{{ $bill_info->ip_remaining_amount }}</span>
                      </div>
                  </div>
                  <div class="col-md-6">
                       <div class="form-group">
                          <label>Collector  </label>
                              <select name="ip_collector_id" required="required" id="IP_COLLECTOR_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Collector">
                                  <option value="">-- Select Collector --</option>
                                  <?php foreach ( $lst_collectors as $key => $tech_info ) { ?>
                                          <option {{ $bill_info->ip_collector_id == $tech_info->id ? "selected"  : "" }}  value="<?php echo $tech_info->id;  ?>"><?php echo $tech_info->u_fullname;  ?></option>
                                  <?php  } ?>
                                  <?php foreach ( $lst_admins as $key => $user_info ) { ?>
                                  <option  {{ $bill_info->ip_collector_id == $user_info->id ? "selected"  : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                  <?php  } ?>

                                  <?php foreach ( $lst_technicians as $key => $user_info ) { ?>
                                  <option  {{ $bill_info->ip_collector_id == $user_info->id ? "selected"  : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
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
                                 <option {{ $bill_info->ip_payment_type_id == $paytype_info->pt_id ? "selected" : "" }} value="{{ $paytype_info->pt_id }}">{{ $paytype_info->pt_payment_type }}</option>
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
                                 <option  {{ $bill_info->ip_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }}&nbsp;-&nbsp;{{ $currency_info->cc_currency_name }}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <br/>
                          <label class="form-check form-switch form-check-custom form-check-solid">
                              <input class="form-check-input" type="checkbox" {{  $bill_info->ip_billing_status == 1 ? "checked" : "" }} name="ip_billing_status" id="IP_BILLING_STATUS"   value="1"  />
                              <span class="form-check-label fw-semibold text-muted">
                                          Pay Bill
                                        </span>
                          </label>
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
    </div>
    </form>
    <div class="row">
        <div class="col-md-12 RVSPayments">
            @foreach($lst_rvc_payments as $index => $rvc_info )
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"> {{  $rvc_info->br_client_code }}-{{  $rvc_info->br_client_name }}</h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-sm btn-light">
                                Action
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Collector : </label><br/>
                                <span class="text-success">{{  $rvc_info->Bill->Collector->u_fullname }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bill Amount : </label><br/>
                                <span class="text-success">{{ $rvc_info->br_bill_amount }}&nbsp;<b>{{ $rvc_info->Currency->cc_currency_code }}</b></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bill Amount Paid : </label><br/>
                                <span class="text-success">{{ $rvc_info->br_paid_amount }}&nbsp;<b>{{ $rvc_info->Currency->cc_currency_code }}</b></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bill Amount Remaining : </label><br/>
                                <span class="text-success">{{ $rvc_info->br_remaining_amount }}&nbsp;<b>{{ $rvc_info->Currency->cc_currency_code }}</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
 </div>

@endsection
