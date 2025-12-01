@extends('layouts.layout', ['page_title' => "Edit Product Item"])

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
            <h3 class="card-title">Edit Item: {{ $item_info->fi_item_name }}</h3>
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
                <input type="hidden" name="mi_id" value="{{ $item_info->mi_id }}">
                <div class="form-body">
                    {!! csrf_field() !!}

                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Item updated successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> Please fix the errors.
                    </div>

                    <div class="row">
                        <div class="col-md-12" align="left">
                            <label>Product Picture</label>
                        </div>

                        <div class="col-md-3">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height:150px;">

                                    <img id="AVATAR_PIC" height="120" src="@if($item_info->mi_image_file_name)
                                        {{ url(Config::get('constants.PRODUCTS_PATH') . $item_info->mi_image_base_src . $item_info->mi_image_file_name . '.' . $item_info->mi_image_extension) }}
                                      @else
                                            {{ url('images/NoImageAvailable.jpg') }}
                                          @endif" alt="item image" />

                                </div>

                                <div class="fileinput-preview fileinput-exists thumbnail"
                                    style="max-width: 200px; max-height: 150px;"> </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="clearfix margin-top-10">
                                <span class="btn default btn-file">
                                    <span class="fileinput-new">Select image</span><br />
                                    <input type="file" name="mi_avatar_pic" id="MI_AVATAR_PIC" />
                                </span>

                                <br><br>
                                <span class="label label-danger"> NOTE! </span><br><br>
                                <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and
                                    Internet Explorer 10 only </span>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" height="50"
                                width="150" /><br />
                            <label class="lblbarcode">{{ $item_info->mi_barcode }}</label>
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Product Barcode</label>
                            <input type="text" name="mi_barcode" id="MI_BARCODE" class="form-control" maxlength="50"
                                value="{{ $item_info->mi_barcode }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Item Name</label>
                            <input type="text" name="mi_item_name" id="MI_ITEM_NAME" class="form-control" maxlength="255"
                                value="{{ $item_info->mi_item_name }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Item Price</label>
                            <input type="text" name="mi_base_price" id="MI_BASE_PRICE" class="form-control" maxlength="10"
                                value="{{ $item_info->mi_base_price }}" />
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Currency</label>
                            <select class="form-select" name="mi_currency_id" id="MI_CURRENCY_ID">
                                <option value="0">-- Select Currency --</option>
                                @foreach($lst_currencies as $currency)
                                    <option value="{{ $currency->cc_id }}" @if($currency->cc_id == $item_info->mi_currency_id)
                                    selected @endif>
                                        {{ $currency->cc_currency_code }} - {{ $currency->cc_currency_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Units</label>
                            <select class="form-select" name="mi_unit_id" id="MI_UNIT_ID">
                                <option value="0">-- Select Unit --</option>
                                @foreach($lst_units as $unit)
                                    <option value="{{ $unit->ss_id }}" @if($unit->ss_id == $item_info->mi_unit_id) selected
                                    @endif>
                                        {{ $unit->ss_status_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="control-label">Category</label>
                            <select class="form-select" name="mi_category_id" id="MI_CATEGORY_ID">
                                <option value="0">-- Select Category --</option>
                                @foreach($lst_categories as $cat)
                                    <option value="{{ $cat->mc_id }}" @if($cat->mc_id == $item_info->mi_category_id) selected
                                    @endif>
                                        {{ $cat->mc_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>SKU Code</label>
                            <input type="text" name="mi_sku_code" id="MI_SKU_CODE" class="form-control"
                                value="{{ $item_info->mi_sku_code }}" />
                        </div>

                        <div class="col-md-4">
                            <label>Preparation Time (minutes)</label>
                            <input type="number" min="0" name="mi_preparation_time_minutes" id="MI_PREPARATION_TIME"
                                class="form-control" value="{{ $item_info->mi_preparation_time_minutes }}" />
                        </div>

                        <div class="col-md-4">
                            <label>Tax Percentage</label>
                            <input type="text" name="mi_tax_percentage" id="MI_TAX_PERCENTAGE" class="form-control"
                                value="{{ $item_info->mi_tax_percentage }}" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label>POS Order Display</label>
                            <select name="mi_pos_order_display" id="MI_POS_ORDER_DISPLAY" class="form-select">
                                <option value="1" @if($item_info->mi_pos_order_display == 1) selected @endif>Show</option>
                                <option value="0" @if($item_info->mi_pos_order_display == 0) selected @endif>Hide</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Calories</label>
                            <input type="number" name="mi_calories" id="MI_CALORIES" class="form-control"
                                value="{{ $item_info->mi_calories }}" />
                        </div>

                        <div class="col-md-3">
                            <label>Cost Price</label>
                            <input type="text" name="mi_cost_price" id="MI_COST_PRICE" class="form-control"
                                value="{{ $item_info->mi_cost_price }}" />
                        </div>

                        <div class="col-md-3">
                            <label>Max Order Quantity</label>
                            <input type="number" name="mi_max_order_quantity" id="MI_MAX_ORDER_QUANTITY"
                                class="form-control" value="{{ $item_info->mi_max_order_quantity }}" />
                        </div>
                    </div>

                    {{-- SWITCHES --}}
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="mi_is_available" id="MI_IS_AVAILABLE"
                                    value="1" @if($item_info->mi_is_available == 1) checked @endif />
                                <span class="form-check-label fw-semibold text-muted">Available</span>
                            </label>
                        </div>

                        <div class="col-md-3">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="mi_is_vegetarian"
                                    id="MI_IS_VEGETARIAN" value="1" @if($item_info->mi_is_vegetarian == 1) checked @endif />
                                <span class="form-check-label fw-semibold text-muted">Vegetarian</span>
                            </label>
                        </div>

                        <div class="col-md-3">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="mi_is_spicy" id="MI_IS_SPICY"
                                    value="1" @if($item_info->mi_is_spicy == 1) checked @endif />
                                <span class="form-check-label fw-semibold text-muted">Spicy</span>
                            </label>
                        </div>

                        <div class="col-md-3">
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="fi_is_active" id="FI_IS_ACTIVE"
                                    value="1" @if($item_info->mi_is_active == 1) checked @endif />
                                <span class="form-check-label fw-semibold text-muted">Active</span>
                            </label>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="row">
                        <div class="col-md-12">
                            <label>Item Description</label>
                            <textarea name="mi_item_description" id="MI_ITEM_DESCRIPTION" class="form-control"
                                rows="3">{{ $item_info->mi_item_description }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end m-8">
                    <button type="submit" name="btn_save_item" id="BTN_SAVE_ITEM" class="btn btn-info me-2">Save</button>
                    <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
                </div>
            </form>




            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Modifiers</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    <div id="LstItemsMain" class="table-responsive">
                        <table class="table table-bordered table-hover" id="html_table" width="100%">
                            <thead>
                                <tr>
                                    <th title="#">#</th>
                                    <th title="Id">ID</th>
                                    <th title="Name">Item Name</th>
                                    <th title="edit">Modifier Name</th>
                                    <th title="override cost">Override Cost</th>
                                    <th title="delete">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="LstItemsModifiers"></tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-10" align="left">
                            <ul id="ItemsModifiersPagination" class="pagination-sm"></ul>
                        </div>
                        <div align="right">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#ModelPopUp">
                                Add Modifier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ModelPopUp" tabindex="2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Modifier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form name="frm_save_items_modifiers" id="FORM_SAVE_ITEM_MODIFIERS">
                        <div class="form-body">
                            <span id="hidden_fields">
                                {!! csrf_field() !!}
                                <input type="hidden" id="FK_MENU_ITEM_ID" name="fk_menu_item_id"
                                    value="{{ $item_info->fi_id }}">

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
                                        <label class="control-label">Modifier</label>
                                        <select class="form-select form-control" data-control="select2" id="FK_MODIFIER_ID"
                                            name="fk_modifier_id">
                                            <option value="0">-- Select Modifier --</option>
                                            @foreach($lst_modifiers as $modifier_info)
                                                <option value="{{ $modifier_info->m_id }}">{{ $modifier_info->m_modifier_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Product</label>
                                        <input type="text" name="product_name" class="form-control" value="">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Currency</label>
                                        <select class="form-select form-control" id="IM_CURRENCY_ID" name="im_currency_id">
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
                                        <label class="control-label">Type</label>
                                        <select class="form-select form-control" data-control="select2" id="IM_TYPE_ID"
                                            name="im_type_id">
                                            <option value="0">-- Select Type --</option>
                                            <option value="1">Add</option>
                                            <option value="2">Remove</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Override Cost</label>
                                        <input type="text" name="im_override_cost" id="IM_OVERRIDE_COST"
                                            class="form-control" required />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="im_is_remove_ingredient"
                                                id="IM_IS_REMOVE_INGREDIENT" value="1" />
                                            <span class="form-check-label fw-semibold text-muted">Remove Ingredient</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" align="right">

                                    <button type="submit" class="btn btn-primary" id="BTN_SAVE_MODIFIER">Save</button>
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
@endsection
