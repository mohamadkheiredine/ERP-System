@extends('layouts.layout', ['page_title' => "Product Item Management"])

@section('themes')
    <style>
        th {
            cursor: pointer;
        }

        #ModelPopUp {
            width: 100%;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/fnb-items.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/fnb/menu-items/saveItem.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Item</h3>
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
            <form name="frm_save_items" id="FORM_SAVE_ITEM">
                <div class="form-body">
                    <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>

                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Item Information is saved successfully!
                    </div>

                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>

                    <div class="row">
                        <div class="col-md-12" align="left">
                            <label>Product Picture </label>
                        </div>
                        <div class="col-md-3">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img id="AVATAR_PIC" height="120" src="{{ url('images/NoImageAvailable.jpg') }}"
                                        alt="" />
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 150px;"> </div>

                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="clearfix margin-top-10">
                                <div>
                                    <span class="btn default btn-file" style="text-align: left;">
                                        <span class="fileinput-new"> Select image </span><br />
                                        <input type="file" name="mi_avatar_pic" id="MI_AVATAR_PIC" /> </span>
                                </div>
                                <br>
                                <span class="label label-danger"> NOTE! </span><br><br>
                                <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and
                                    Internet Explorer 10 only </span>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode"
                                    height="50" width="150" /><br />
                                <label class='lblbarcode'>{{ $rand_barcode }}</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Product Barcode <span class="required"></span></label>
                                <input type="text" name="mi_barcode" id="MI_BARCODE" class="form-control"
                                    required="required" maxlength="50" value="{{ $rand_barcode }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Item Name <span class="required"></span></label>
                                <input type="text" name="mi_item_name" id="MI_ITEM_NAME" class="form-control" required
                                    maxlength="255" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Item Price <span class="required"></span></label>
                                <input type="text" name="mi_base_price" id="MI_BASE_PRICE" class="form-control" required
                                    maxlength="10" value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Currency <span class="required"></span></label>
                                <select class="form-select form-control" data-control="select2" id="MI_CURRENCY_ID"
                                    name="mi_currency_id" required>
                                    <option value="0">-- Select Currency --</option>
                                    @foreach($lst_currencies as $index => $currency_info)
                                        <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }} -
                                            {{ $currency_info->cc_currency_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Units</label>
                                <select class="form-select form-control" data-control="select2" id="MI_UNIT_ID"
                                    name="mi_unit_id">
                                    <option value="0">-- Select Unit --</option>
                                    @foreach($lst_units as $index => $unit_info)
                                        <option value="{{ $unit_info->su_id }}">{{ $unit_info->su_unit_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <label class="control-label">Category</label>
                                <select class="form-select form-control" data-control="select2" id="MI_CATEGORY_ID"
                                    name="mi_category_id">
                                    <option value="0">-- Select Category --</option>
                                    @foreach($lst_categories as $index => $category_info)
                                        <option value="{{ $category_info->mc_id }}">{{ $category_info->mc_category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Kitchen Station</label>
                                <select class="form-select form-control" data-control="select2" id="MI_KITCHEN_STATION_ID"
                                    name="mi_kitchen_station_id">

                                    <option value="0">-- Select Kitchen --</option>

                                    @foreach($lst_kitchens as $kitchen)
                                        <option value="{{ $kitchen->ks_id }}">
                                            {{ $kitchen->ks_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">SKU Code</label>
                                <input type="text" name="mi_sku_code" id="MI_SKU_CODE" class="form-control"
                                    value="{{ $rand_barcode }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Preparation Time (minutes)</label>
                                <input type="number" name="mi_preparation_time_minutes" id="MI_PREPARATION_TIME"
                                    class="form-control" min="0" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tax Percentage</label>
                                <input type="text" name="mi_tax_percentage" id="MI_TAX_PERCENTAGE" class="form-control"
                                    maxlength="5" />
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">POS Order Display</label>
                                <select name="mi_pos_order_display" id="MI_POS_ORDER_DISPLAY" class="form-select">
                                    <option value="1">Show</option>
                                    <option value="0">Hide</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Calories</label>
                                <input type="number" name="mi_calories" id="MI_CALORIES" class="form-control" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Cost Price</label>
                                <input type="text" name="mi_cost_price" id="MI_COST_PRICE" class="form-control"
                                    maxlength="10" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Max Order Quantity</label>
                                <input type="number" name="mi_max_order_quantity" id="MI_MAX_ORDER_QUANTITY"
                                    class="form-control" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Loyalty Points</label>
                                <input type="number" name="mi_loyalty_points" id="MI_LOYALTY_POINTS"
                                    class="form-control" min="0" value="0" />
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="mi_is_available"
                                        id="MI_IS_AVAILABLE" value="1" />
                                    <span class="form-check-label fw-semibold text-muted">Available</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="mi_is_vegetarian"
                                        id="MI_IS_VEGETARIAN" value="1" />
                                    <span class="form-check-label fw-semibold text-muted">Vegetarian</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="mi_is_spicy" id="MI_IS_SPICY"
                                        value="1" />
                                    <span class="form-check-label fw-semibold text-muted">Spicy</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="fi_is_active" id="FI_IS_ACTIVE"
                                        value="1" />
                                    <span class="form-check-label fw-semibold text-muted">
                                        Active
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Item Description</label>
                                <textarea name="mi_item_description" id="MI_ITEM_DESCRIPTION" class="form-control"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-flex justify-content-end m-8">
                    <button type="submit" name="btn_save_item" id="BTN_SAVE_ITEM" class="btn btn-info me-2">Save</button>
                    <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
                </div>
            </form>
        </div>
    </div>
@endsection
