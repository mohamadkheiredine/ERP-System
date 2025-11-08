@extends('layouts.layout',['page_title' => "FNB Tables Management"])

@section('themes')
<style>
  th {
    cursor: pointer;
  }

  #ModelPopUp {
    width: 800px;
  }

  /* Optional: make inputs consistent full width */
  .form-group {
    margin-bottom: 1.5rem;
  }

</style>
@endsection

@section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/fnb-tables.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/tables/saveTable.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Add New Table</h3>
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
    <form name="frm_save_tables" id="FORM_SAVE_TABLE">
      <div class="form-body">
        <span id="hidden_fields">
          {!! csrf_field() !!}
        </span>

        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> Table Information is saved successfully!
        </div>

        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>
            <div class="row">
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Table Name <span class="required"></span></label>
          <input type="text" name="ft_label" id="FT_TABLE_NAME" class="form-control" required maxlength="255" value="" />
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Floor <span class="required"></span></label>
          <select class="form-select form-control" data-control="select2" id="FL_ID" name="fl_id">
            <option value="0">-- Select Floor --</option>
            @foreach($lst_floors as $index => $floor_info)
            <option value="{{ $floor_info->fl_id }}">{{ $floor_info->fl_floor_name }}</option>
            @endforeach
          </select>
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Capacity <span class="required"></span></label>
          <input type="number" name="ft_capacity" id="FT_CAPACITY" class="form-control" min="2" value="2" required placeholder="Enter number of seats" />
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">X Pos <span class="required"></span></label>
          <input type="text" name="ft_x_pos" id="FT_X_POS" class="form-control" required placeholder="Enter X position">
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Y Pos <span class="required"></span></label>
          <input type="text" name="ft_y_pos" id="FT_Y_POS" class="form-control" required placeholder="Enter Y position">
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Rotation <span class="required"></span></label>
          <input type="text" name="ft_rotation" id="FT_ROTATION" class="form-control" min="0" required>
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Shape <span class="required"></span></label>
          <input type="number" name="ft_shape" id="FT_SHAPE" class="form-control" min="0" required>
        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <label class="control-label">Color <span class="required"></span></label>
          <input type="color" name="ft_color" id="FT_COLOR" class="form-control" value="#000000" required>

        </div>
          </div>
          <div class="col-md-4 col-xs-12">
        <div class="form-group">
          <br />
          <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" name="ft_active" id="FT_ACTIVE" value="1" />
            <span class="form-check-label fw-semibold text-muted">
              Table Active
            </span>
          </label>
        </div>
          </div>
          <div class="col-md-12 col-xs-12">
        <div class="d-flex justify-content-end">
          <button type="submit" name="btn_save_tables" id="BTN_SAVE_TABLE" class="btn btn-info me-2">Save</button>
          <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
        </div>
          </div></div>
      </div>
    </form>
  </div>
</div>
@endsection
