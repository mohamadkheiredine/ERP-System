@extends('layouts.layout',['page_title' => "Ingredients Management"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-receipes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/receipes/savereceipes.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Add New ingredient</h3>
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
    <form name="frm_save_ingredients" id="FORM_SAVE_INGREDIENTS">
      <div class="form-body">
        <span id="hidden_fields">
          {!! csrf_field() !!}
          <input type="hidden" name="item_id" value="{{ $item_id }}">
        </span>

        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> ingredient Information is saved successfully!
        </div>

        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>

        <div class="row">

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Product Name <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="IN_INGREDIENT_NAME" name="in_product_id">
                <option value="0">-- Select Product --</option>
                @foreach($lst_products as $index => $product_info)
                <option value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label"> Stock Quantity <span class="required"> * </span></label>
              <input type="number" step="0.01" class="form-control qty-input" name="in_stock_quantity">
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Unit <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="IN_UNIT_OF_MEASURE" name="in_unit_of_measure">
                <option value="0">-- Select Unit --</option>
                @foreach($lst_unit_of_measure as $index => $unit_info)
                <option value="{{ $unit_info->su_id }}">{{ $unit_info->su_unit_label }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">

            <div class="form-group">
              <label class="control-label">Waste (%) <span class="required"></span></label>
              <input type="number" step="1" class="form-control waste-input" name="in_waste_percent" id="IN_WASTE_PERCENT">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Cost Per Unit <span class="required"></span></label>
              <input type="text" class="form-control waste-input" name="in_cost_per_unit" id="IN_COST_PER_UNIT">
            </div>
          </div>

        </div>

        <div class="row">
            <label class="control-label">Notes <span class="required"></span></label>
            <textarea class="form-control" rows="4" placeholder="Write something..." name="in_notes" id="IN_NOTES"></textarea>
        </div>
      </div>
  </div>


  <div class="d-flex justify-content-end m-8">
    <button type="submit" name="btn_save_ingredient" id="BTN_SAVE_INGREDIENT" class="btn btn-info me-2">Save</button>
    <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
  </div>
</div>
</form>
</div>
</div>
@endsection
