@extends('layouts.layout',['page_title' => "Product Item Management"])

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
        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Item Name <span class="required"></span></label>
              <input type="text" name="fi_item_name" id="FI_ITEM_NAME" class="form-control" required maxlength="255" value="" />
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <img id="BARCODE_IMG" src="data:image/png;base64,{{ $bar_code_png }}" alt="barcode" height="50" width="150" /><br />
              <label class='lblbarcode'>{{ $rand_barcode }}</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label"> Product Barcode <span class="required"> * </span></label>
              <input type="text" name="p_bar_code" id="P_BAR_CODE" class="form-control" required="required" maxlength="50" value="{{ $rand_barcode }}" />
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Company <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FI_BRANCH_ID" name="fi_branch_id">
                <option value="0">-- Select Company --</option>
                @foreach($lst_companies as $index => $company_info)
                <option value="{{ $company_info->cd_id }}">{{ $company_info->cd_company_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">

            <div class="form-group">
              <label class="control-label">Category <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FI_CATEGORY_ID" name="fi_category_id">
                <option value="0">-- Select Category --</option>
                @foreach($lst_categories as $index => $category_info)
                <option value="{{ $category_info->mc_id }}">{{ $category_info->mc_category_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Kitchen <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FI_KITCHEN_ID" name="fi_kitchen_id">
                <option value="0">-- Select Kitchen --</option>
                @foreach($lst_kitchens as $index => $kitchen_info)
                <option value="{{ $kitchen_info->ks_id }}">{{ $kitchen_info->ks_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Station <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FI_STATION_ID" name="fi_station_id">
                <option value="0">-- Select Station --</option>
                @foreach($lst_stations as $index => $station_info)
                <option value="{{ $station_info->pt_id }}">{{ $station_info->pt_terminal_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">TAX <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="FI_TAX_ID" name="fi_tax_id">
                <option value="0">-- Select TAX --</option>
                @foreach($lst_taxes as $index => $tax_info)
                <option value="{{ $tax_info->av_id }}">{{ $tax_info->av_vat_label }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <br />
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="fi_is_active" id="FI_IS_ACTIVE" value="1" />
                <span class="form-check-label fw-semibold text-muted">
                  Active
                </span>
              </label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <br />
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="fi_is_sellable" id="FI_IS_SELLABLE" value="1" />
                <span class="form-check-label fw-semibold text-muted">
                  sellable
                </span>
              </label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <br />
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="fi_is_stock_item" id="FI_IS_STOCK_ITEM" value="1" />
                <span class="form-check-label fw-semibold text-muted">
                  stock item
                </span>
              </label>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <br />
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="fi_print_to_kitchen" id="FI_PRINT_TO_KITCHEN" value="1" />
                <span class="form-check-label fw-semibold text-muted">
                  print to kitchen
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>
  </div>


  <div class="d-flex justify-content-end m-8">
    <button type="submit" name="btn_save_item" id="BTN_SAVE_ITEM" class="btn btn-info me-2">Save</button>
    <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
  </div>
</div>
</form>
</div>
</div>
@endsection
