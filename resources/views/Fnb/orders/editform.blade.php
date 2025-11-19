@extends('layouts.layout',['page_title' => "FNB orders Management"])

@section('themes')
<style>
  th {
    cursor: pointer;
  }

  #ModelPopUp {
    width: 800px;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

</style>
@endsection

@section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/fnb-orders.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/orders/saveorders.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Add New orders</h3>
    <div class="card-toolbar">
      <div class="btn-group">
        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          Action
        </button>
        <ul class="dropdown-menu"></ul>
      </div>
    </div>
  </div>

  <div class="card-body">
    <form name="frm_save_orders" id="FORM_SAVE_ORDER">
      <div class="form-body">
        <span id="hidden_fields">
          {!! csrf_field() !!}
          <input type="hidden" name="fo_id" id="FO_ID" value="{{ $order_info->fo_id }}">
        </span>

        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> orders Information is saved successfully!
        </div>

        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>

        <div class="row">

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Code <span class="required"></span></label>
              <input type="text" name="fo_order_code" id="FO_ORDER_CODE" class="form-control" required maxlength="255" value="{{ $order_info->fo_order_code }}" />
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Company <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="PS_COMPANY_ID" name="fo_branch_id" name="lead_category">
                <option value="0">-- Select Company --</option>
                @foreach($lst_companies as $index => $company_info)
                <option value="{{ $company_info->cd_id }}" @if($company_info->cd_id == $order_info->fo_branch_id) selected @endif>{{ $company_info->cd_company_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Type <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FO_ORDER_TYPE" name="fo_order_type" name="lead_category">
                <option value="0">-- Select Type --</option>
                <option value="dine_in" @if($order_info->fo_order_type=='dine_in') selected @endif>Dine in</option>
                <option value="takeaway" @if($order_info->fo_order_type=='takeaway') selected @endif>Takeaway</option>
                <option value="delivery" @if($order_info->fo_order_type=='delivery') selected @endif>Delivery</option>
                <option value="online" @if($order_info->fo_order_type=='online') selected @endif>Online</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Store <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FO_STORE_ID" name="fo_store_id" name="lead_category">
                <option value="0">-- Select Store --</option>
                @foreach($lst_stores as $index => $store_info)
                <option value="{{ $store_info->ps_id }}" @if($store_info->ps_id == $order_info->fo_store_id) selected @endif>{{ $store_info->ps_store_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Table <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FO_TABLE_ID" name="fo_table_id" name="lead_category">
                <option value="0">-- Select Table --</option>
                @foreach($lst_tables as $index => $table_info)
                <option value="{{ $table_info->ft_id }}" @if($table_info->ft_id == $order_info->fo_table_id) selected @endif>{{ $table_info->ft_label }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Customers <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FO_CUSTOMER_ID" name="fo_customer_id" name="lead_category">
                <option value="0">-- Select Customer --</option>
                @foreach($lst_customers as $index => $customer_info)
                <option value="{{ $customer_info->ic_id }}" @if($customer_info->ic_id == $order_info->fo_customer_id) selected @endif>{{ $customer_info->ic_customer_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Status <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FO_ORDER_STATUS" name="fo_order_status" name="lead_category">
                <option value="0">-- Select Status --</option>
                  @foreach ( $lst_order_status as $key => $status_info )
                        <option value="{{ $status_info->ss_id }}" @if($status_info->ss_id == $order_info->fo_order_status) selected @endif>{{ $status_info->ss_status_title }}</option>
                   @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Subtotal <span class="required"></span></label>
              <input type="number" name="fo_subtotal" id="FO_SUBTOTAL" class="form-control" required value="{{ $order_info->fo_subtotal }}" />
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order discount <span class="required"></span></label>
              <input type="number" name="fo_discount" id="FO_DISCOUNT" class="form-control" required value="{{ $order_info->fo_discount }}" />
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Tax <span class="required"></span></label>
              <input type="number" name="fo_tax" id="FO_TAX" class="form-control" required value="{{ $order_info->fo_tax }}" />
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Service Charge <span class="required"></span></label>
              <input type="number" name="fo_service_charge" id="FO_SERVICE_CHARGE" class="form-control" required value="{{ $order_info->fo_service_charge }}" />
            </div>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Total Amount <span class="required"></span></label>
              <input type="number" name="fo_total_amount" id="FO_TOTAL_AMOUNT" class="form-control" required value="{{ $order_info->fo_total_amount }}" />
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Paid Amount <span class="required"></span></label>
              <input type="number" name="fo_paid_amount" id="FO_PAID_AMOUNT" class="form-control" required value="{{ $order_info->fo_paid_amount }}" />
            </div>
          </div>

          <div class="col-md-4">
            <label class="control-label">Currency <span class="required"></span></label>
            <select class="form-select form-control" data-control="select2" id="CC_ID" name="cc_id" name="lead_category">
              <option value="0">-- Select Currency --</option>
              @foreach($lst_currencies as $index => $currency_info)
              <option value="{{ $currency_info->cc_id }}" @if($currency_info->cc_id == $order_info->fo_currency_id) selected @endif>{{ $currency_info->cc_currency_code }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Order Payment Status <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FO_PAYMENT_STATUS" name="fo_payment_status" name="lead_category">
                <option value="0">-- Select payment status --</option>
                <option value="unpaid" @if($order_info->fo_payment_status=='unpaid') selected @endif>Unpaid</option>
                <option value="partial" @if($order_info->fo_payment_status=='partial') selected @endif>Partial</option>
                <option value="paid" @if($order_info->fo_payment_status=='paid') selected @endif>Paid</option>
              </select>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-12 col-xs-12">
            <div class="form-group">
              <label class="control-label">Notes <span class="required"></span></label>
              <textarea name="fo_notes" id="FO_NOTES" class="form-control" rows="3" placeholder="Enter orders notes..." required>{{ $order_info->fo_notes }}</textarea>
            </div>
          </div>
        </div>

        <div class="col-md-12 col-xs-12">
          <div class="d-flex justify-content-end">
            <button type="submit" name="btn_save_orders" id="BTN_SAVE_ORDER" class="btn btn-info me-2">Save</button>
            <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
          </div>
        </div>
      </div>





  </div>
  </form>
</div>
@endsection
