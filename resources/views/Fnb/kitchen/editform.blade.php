@extends('layouts.layout',['page_title' => "Fnb Kitchens Management"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-kitchen.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/kitchen/saveKitchen.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Edit Existing Kitchen</h3>
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
    <form name="frm_save_kitchen" id="FORM_SAVE_KITCHEN">
      <div class="form-body">
        <span id="hidden_fields">
          <input type="hidden" name="ks_id" value="{{ $kitchen_info->ks_id }}" />
          {!! csrf_field() !!}
        </span>
        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> Kitchen Information is saved successfully!
        </div>
        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>
          <div class="row">
                  <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Kitchen Name <span class="required">*</span></label>
              <input type="text" name="ks_name" id="KS_NAME" class="form-control" required maxlength="255" value="{{ $kitchen_info->ks_name }}">
            </div>
                  </div>
                  <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <label class="control-label">Company <span class="required">*</span></label>
              <select class="form-select form-control" data-control="select2" id="KS_BRANCH_ID" name="ks_branch_id" required>
                <option value="0">-- Select Company --</option>
                @foreach($lst_companies as $company_info)
                <option  {{ $kitchen_info->ks_branch_id == $company_info->cd_id  ? "selected" : "" }} value="{{ $company_info->cd_id }}" {{ $kitchen_info->ks_branch_id == $company_info->cd_id ? 'selected' : '' }}>
                  {{ $company_info->cd_company_name }}
                </option>
                @endforeach
              </select>
            </div>
                  </div>
              <div class="col-md-12 col-xs-12">

            <div class="form-group">
              <label class="control-label">Description <span class="required"></span></label>
              <textarea name="ks_description" id="KS_DESCRIPTION" class="form-control" rows="3" placeholder="Enter kitchen description" required >{{ $kitchen_info->ks_description }}</textarea>
            </div>
              </div>
              <div class="col-md-4 col-xs-12">
            <div class="form-group">
              <br />
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="ks_active" id="KS_ACTIVE" {{ $kitchen_info->ks_is_active ? "checked" : "" }} value="1" />
                <span class="form-check-label fw-semibold text-muted">
                  Kitchen Active
                </span>
              </label>
            </div>
              </div>
            <div class="row" style="height:5px;"></div>
            <div class="row">
              <div class="col-md-9"></div>
              <div class="col-md-3" align="right">
                <button type="submit" name="btn_save_kitchen" id="BTN_SAVE_KITCHEN" class="btn btn-info">Save</button>
                <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
              </div>
            </div>
          </div>
      </div>
    </form>
  </div>
</div>
@endsection
