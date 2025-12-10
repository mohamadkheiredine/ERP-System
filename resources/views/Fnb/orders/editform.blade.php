@extends('layouts.layout', ['page_title' => "FNB orders Management"])

@section('themes')
    <style>
        th {
            cursor: pointer;
        }

        #ModelPopUp {
            width: 100%;
        }

        #ModelPopUpModifiers {
            width: 100%;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        #DELIVERY_TAB {
            display: none;
        }
    </style>
@endsection

@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/fnb-orders.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/fnb/orders/saveorders.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New orders</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
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
                                <label class="control-label"> Order Code :&nbsp;</label><br />
                                <input type="text" name="fo_order_code" id="FO_ORDER_CODE" class="form-control"
                                    readonly="readonly" required="required" maxlength="25" tabindex="1"
                                    value="{{ $order_info->fo_order_code != null ? $order_info->fo_order_code : $order_code }}" />
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Company <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="PS_COMPANY_ID"
                                    name="fo_branch_id" name="lead_category">
                                    <option value="0">-- Select Company --</option>
                                    @foreach($lst_companies as $index => $company_info)
                                        <option value="{{ $company_info->cd_id }}"
                                            @if($company_info->cd_id == $order_info->fo_branch_id) selected @endif>
                                            {{ $company_info->cd_company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Type <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="FO_ORDER_TYPE"
                                    name="fo_order_type" name="lead_category">
                                    <option value="0">-- Select Type --</option>
                                    <option value="dine_in" @if($order_info->fo_order_type == 'dine_in') selected @endif>Dine
                                        in</option>
                                    <option value="takeaway" @if($order_info->fo_order_type == 'takeaway') selected @endif>
                                        Takeaway</option>
                                    <option value="delivery" @if($order_info->fo_order_type == 'delivery') selected @endif>
                                        Delivery</option>
                                    <option value="online" @if($order_info->fo_order_type == 'online') selected @endif>Online
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Store <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="FO_STORE_ID"
                                    name="fo_store_id" name="lead_category">
                                    <option value="0">-- Select Store --</option>
                                    @foreach($lst_stores as $index => $store_info)
                                        <option value="{{ $store_info->ps_id }}"
                                            @if($store_info->ps_id == $order_info->fo_store_id) selected @endif>
                                            {{ $store_info->ps_store_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Table <span class="required"></span></label>
                                <select id="FO_TABLE_ID" name="fo_table_id[]" class="form-control" multiple
                                    data-control="select2">

                                    @foreach($lst_tables as $table)
                                        <option value="{{ $table->ft_id }}" @if(in_array($table->ft_id, $selected_table_ids))
                                        selected @endif>
                                            {{ $table->ft_label }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Customers <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="FO_CUSTOMER_ID"
                                    name="fo_customer_id" name="lead_category">
                                    <option value="0">-- Select Customer --</option>
                                    @foreach($lst_customers as $index => $customer_info)
                                        <option value="{{ $customer_info->ic_id }}"
                                            @if($customer_info->ic_id == $order_info->fo_customer_id) selected @endif>
                                            {{ $customer_info->ic_customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Status <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="FO_ORDER_STATUS"
                                    name="fo_order_status" name="lead_category">
                                    <option value="0">-- Select Status --</option>
                                    @foreach ($lst_order_status as $key => $status_info)
                                        <option value="{{ $status_info->ss_id }}"
                                            @if($status_info->ss_id == $order_info->fo_order_status) selected @endif>
                                            {{ $status_info->ss_status_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Subtotal <span class="required"></span></label>
                                <input type="number" name="fo_subtotal" id="FO_SUBTOTAL" class="form-control" required
                                    value="{{ $order_info->fo_subtotal }}" />
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order discount <span class="required"></span></label>
                                <input type="number" name="fo_discount" id="FO_DISCOUNT" class="form-control" required
                                    value="{{ $order_info->fo_discount }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Tax <span class="required"></span></label>
                                <input type="number" name="fo_tax" id="FO_TAX" class="form-control" required
                                    value="{{ $order_info->fo_tax }}" />
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Service Charge <span class="required"></span></label>
                                <input type="number" name="fo_service_charge" id="FO_SERVICE_CHARGE" class="form-control"
                                    required value="{{ $order_info->fo_service_charge }}" />
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Total Amount <span class="required"></span></label>
                                <input type="number" name="fo_total_amount" id="FO_TOTAL_AMOUNT" class="form-control"
                                    required value="{{ $order_info->fo_total_amount }}" />
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Paid Amount <span class="required"></span></label>
                                <input type="number" name="fo_paid_amount" id="FO_PAID_AMOUNT" class="form-control" required
                                    value="{{ $order_info->fo_paid_amount }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Currency <span class="required"></span></label>
                            <select class="form-select form-control" data-control="select2" id="CC_ID" name="cc_id"
                                name="lead_category">
                                <option value="0">-- Select Currency --</option>
                                @foreach($lst_currencies as $index => $currency_info)
                                    <option value="{{ $currency_info->cc_id }}"
                                        @if($currency_info->cc_id == $order_info->fo_currency_id) selected @endif>
                                        {{ $currency_info->cc_currency_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Order Payment Status <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="FO_PAYMENT_STATUS"
                                    name="fo_payment_status" name="lead_category">
                                    <option value="0">-- Select payment status --</option>
                                    <option value="unpaid" @if($order_info->fo_payment_status == 'unpaid') selected @endif>
                                        Unpaid</option>
                                    <option value="partial" @if($order_info->fo_payment_status == 'partial') selected @endif>
                                        Partial</option>
                                    <option value="paid" @if($order_info->fo_payment_status == 'paid') selected @endif>Paid
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="fo_is_paid" id="FO_IS_PAID"
                                        value="1" {{ $order_info->fo_is_paid == 1 ? 'checked' : '' }} />

                                    <span class="form-check-label fw-semibold text-muted">
                                        Paid
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Kitchen Status <span class="required"></span></label>

                                <select class="form-select form-control" data-control="select2" id="OI_KITCHEN_STATUS"
                                    name="oi_kitchen_status">

                                    <option value="0">-- Select Status --</option>

                                    @foreach ($lst_kitchen_status as $status_info)
                                        <option value="{{ $status_info->ss_id }}"
                                            @if($status_info->ss_id == $order_info->fo_kitchen_status) selected @endif>
                                            {{ $status_info->ss_status_title }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Notes <span class="required"></span></label>
                                <textarea name="fo_notes" id="FO_NOTES" class="form-control" rows="3"
                                    placeholder="Enter orders notes..." required>{{ $order_info->fo_notes }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7"></div>
                        <div class="col-md-5" align="right">
                            <button type="submit" name="btn_save_order" id="BTN_SAVE_ORDER"
                                class="btn btn-info">Save</button>
                            @if($order_info->fo_is_paid == 0)
                                <button type="submit" id="BTN_PAY_ORDER" name="btn_save_order" class="btn btn-danger">Pay Order</button>
                            @endif
                            <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
                            <button type="button" id="BTN_CLOSE_PAGE" name="btn_close_page"
                                class="btn btn-success">Close</button>
                        </div>
                    </div>

                </div>

        </div>
        </form>



        <div class="m-6">
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Items</a>
                </li>
                <li class="nav-item" id="DELIVERY_TAB">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Deliveries</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">

                    <div id="LstItemsMain" class="table-responsive">
                        <table class="table table-bordered table-hover" id="html_table" width="100%">
                            <thead>
                                <tr>
                                    <th title="#">#</th>
                                    <th title="item name">Name</th>
                                    <th title="Name">Item Quantity</th>
                                    <th title="edit">Item Unit Price</th>
                                    <th title="override cost">Item Discount</th>
                                    <th title="modifiers">Item Modifiers</th>
                                    <th title="delete">Delete</th>
                                    <th title="total price">Total Price</th>
                                </tr>
                            </thead>
                            <tbody id="LstItemsOrders"></tbody>
                            <tfoot id="ItemsOrdersTotal">
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-10" align="left">
                            <ul id="ItemsOrdersPagination" class="pagination-sm"></ul>
                        </div>
                        <div align="right">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModelPopUp">
                                Add Item
                            </button>
                        </div>
                    </div>

                    {{-- modal here --}}
                    <div class="modal fade" id="ModelPopUp" tabindex="2" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <form name="frm_save_items_orders" id="FORM_SAVE_ITEM_ORDERS">
                                        <div class="form-body">
                                            <span id="hidden_fields">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="oi_order_id" id="OI_ORDER_ID"
                                                    value="{{ $order_info->fo_id }}">

                                            </span>

                                            <div class="alert alert-success" style="display:none">
                                                <strong>Success!</strong> Information is saved successfully!
                                            </div>

                                            <div class="alert alert-danger" style="display:none">
                                                <strong>Error!</strong> You have some form errors. Please check below.
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Item <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="OI_ITEM_ID" name="oi_item_id">
                                                            <option value="0">-- Select Item --</option>
                                                            @foreach($lst_items as $item_info)
                                                                <option value="{{ $item_info->mi_id }}">
                                                                    {{ $item_info->mi_item_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Quantity <span
                                                                class="required"></span></label>
                                                        <input type="number" name="oi_quantity" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Currency <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" id="OI_CURRENCY_ID"
                                                            name="oi_currency_id">
                                                            <option value="0">-- Select Currency --</option>
                                                            @foreach($lst_currencies as $currency_info)
                                                                <option value="{{ $currency_info->cc_id }}">
                                                                    {{ $currency_info->cc_currency_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Price <span
                                                                class="required"></span></label>
                                                        <input type="number" name="oi_unit_price" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Item Discount <span
                                                                class="required"></span></label>
                                                        <input type="number" name="oi_item_discount" id="OI_ITEM_DISCOUNT"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Kitchen Status <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="OI_KITCHEN_STATUS" name="oi_kitchen_status"
                                                            name="lead_category">
                                                            <option value="0">-- Select Status --</option>
                                                            @foreach ($lst_kitchen_status as $key => $status_info)
                                                                <option value="{{ $status_info->ss_id }}">
                                                                    {{ $status_info->ss_status_title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Kitchen <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="OI_STATION_ID" name="oi_station_id" name="lead_category">
                                                            <option value="0">-- Select Station --</option>
                                                            @foreach ($lst_stations as $key => $station_info)
                                                                <option value="{{ $station_info->ks_id }}">
                                                                    {{ $station_info->ks_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="control-label">Notes <span
                                                            class="required"></span></label>
                                                    <textarea name="oi_notes" id="OI_NOTES" class="form-control" rows="3"
                                                        placeholder="Enter notes..." required></textarea>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-12" align="right">

                                                    <button type="submit" class="btn btn-primary"
                                                        id="BTN_SAVE_ITEM_ORDER">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- modal here --}}
                    <div class="modal fade" id="ModelPopUpModifiers" tabindex="2" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifiers</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div id="LstItemsModifiersMain" class="table-responsive">
                                        <table class="table table-bordered table-hover" id="html_table" width="100%">
                                            <thead>
                                                <tr>
                                                    <th title="#">#</th>
                                                    <th title="Id">ID</th>
                                                    <th title="Name">Name</th>
                                                    <th title="type">Type</th>
                                                    <th title="cost">Cost</th>
                                                    <th title="delete">Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody id="LstItemsOrdersModifiers"></tbody>
                                        </table>
                                    </div>
                                    <form name="frm_save_items_orders_modifiers" id="FORM_SAVE_ITEM_ORDERS_MODIFIERS">
                                        <div class="form-body">
                                            <span id="hidden_fields">
                                                {!! csrf_field() !!}
                                                <input type="hidden" id="IM_ITEM_ID" name="im_item_id">
                                            </span>


                                            <div class="row">

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Modifier Cost <span
                                                                class="required"></span></label>
                                                        <input type="number" name="im_modifier_cost" id="IM_MODIFIER_COST"
                                                            class="form-control" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Modifier <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="IM_MODIFIER_ID" name="im_modifier_id" name="lead_category">
                                                            <option value="0">-- Select Modifier --</option>
                                                            @foreach($lst_modifiers as $index => $modifier_info)
                                                                <option value="{{ $modifier_info->m_id }}">
                                                                    {{ $modifier_info->m_modifier_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" name="im_modifier_name" id="IM_MODIFIER_NAME">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Modifier Type <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="IM_MODIFIER_TYPE" name="im_modifier_type"
                                                            name="lead_category">
                                                            <option value="0">-- Select Type --</option>
                                                            <option value="add">Add</option>
                                                            <option value="remove">Remove</option>
                                                            <option value="option">Option</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Currency <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="IM_CURRENCY_ID" name="im_currency_id" name="lead_category">
                                                            <option value="0">-- Select Currency --</option>
                                                            @foreach($lst_currencies as $index => $currency_info)
                                                                <option value="{{ $currency_info->cc_id }}">
                                                                    {{ $currency_info->cc_currency_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12" align="right">

                                                    <button type="submit" class="btn btn-primary"
                                                        id="BTN_SAVE_ITEM_ORDER_MODIFIER">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">

                    <div id="LstDeliveriesMain" class="table-responsive">
                        <table class="table table-bordered table-hover" id="html_table" width="100%">
                            <thead>
                                <tr>
                                    <th title="#">#</th>
                                    <th title="Address">Address</th>
                                    <th title="Cost">Cost</th>
                                    <th title="Delete">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="LstDeliveries"></tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-10" align="left">
                            <ul id="DeliveryPagination" class="pagination-sm"></ul>
                        </div>
                        <div align="right">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#DeliveryPopUp">
                                Add Delivery
                            </button>
                        </div>
                    </div>

                    {{-- modal here --}}
                    <div class="modal fade" id="DeliveryPopUp" tabindex="2" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delivery</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <form name="frm_save_delivery" id="FORM_SAVE_DELIVERY">
                                        <div class="form-body">
                                            <span id="hidden_fields">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="od_order_id" id="OD_ORDER_ID"
                                                    value="{{ $order_info->fo_id }}">

                                            </span>

                                            <div class="alert alert-success" style="display:none">
                                                <strong>Success!</strong> Information is saved successfully!
                                            </div>

                                            <div class="alert alert-danger" style="display:none">
                                                <strong>Error!</strong> You have some form errors. Please check below.
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Status <span
                                                                class="required"></span></label>
                                                        <select class="form-select form-control" data-control="select2"
                                                            id="OD_DELIVERY_STATUS" name="od_delivery_status">
                                                            <option value="0">-- Select Status --</option>
                                                            @foreach($lst_statuses as $status_info)
                                                                <option value="{{ $status_info->ss_id }}">
                                                                    {{ $status_info->ss_status_title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Address <span
                                                                class="required"></span></label>
                                                        <input type="text" name="od_delivery_address" class="form-control">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Customer Name <span
                                                                class="required"></span></label>
                                                        <input type="text" name="ic_customer_name" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Customer Phone Number <span
                                                                class="required"></span></label>
                                                        <input type="text" name="ic_customer_phone" id="IC_CUSTOMER_PHONE"
                                                            class="form-control" required />
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Delivery Cost <span
                                                                class="required"></span></label>
                                                        <input type="text" name="od_delivery_cost" id="OD_DELIVERY_COST"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-12" align="right">

                                                    <button type="submit" class="btn btn-primary"
                                                        id="BTN_SAVE_DELIVERY">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
