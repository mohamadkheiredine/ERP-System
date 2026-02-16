@extends('layouts.layout',['page_title' => "Floors Management"])

@section('themes')
<style>
  th {
    cursor: pointer;
  }

  #ModelPopUp {
    width: 800px;
  }

</style>
@endsection

@section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/fnb.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/floors/savefloor.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Edit Existing Floor</h3>
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
    <form name="frm_save_floor" id="FORM_SAVE_FLOOR">
      <div class="form-body">
        <span id="hidden_fields">
          <input type="hidden" name="fl_id" value="{{ $floor_info->fl_id }}" />
          {!! csrf_field() !!}
        </span>
        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> Floor Information is saved successfully!
        </div>
        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="form-group">
              <label class="control-label">Floor Name <span class="required"> * </span></label>
              <input type="text" name="fl_floor_name" id="PS_FLOOR_NAME" class="form-control" required maxlength="255" value="{{ $floor_info->fl_floor_name }}" />
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="form-group">
              <label class="control-label">Store <span class="required"> * </span></label>
              <select name="fl_store_id" id="FL_STORE_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Store" required>
                <option value="">Select Store</option>
                @foreach ($lst_stores as $store)
                  <option value="{{ $store->ps_id }}" {{ $floor_info->fl_store_id == $store->ps_id ? 'selected' : '' }}>{{ $store->ps_store_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="row" style="height:5px;"></div>
        <div class="row">
          <div class="col-md-9"></div>
          <div class="col-md-3" align="right">
            <button type="submit" name="btn_save_floor" id="BTN_SAVE_FLOOR" class="btn btn-info">Save</button>
            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
