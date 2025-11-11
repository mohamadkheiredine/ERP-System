@extends('layouts.layout',['page_title' => "FNB Modifiers Management"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-modifiers.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/modifiers/saveModifier.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Edit Modifier</h3>
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
    <form name="frm_save_modifiers" id="FORM_SAVE_MODIFIER">
      <div class="form-body">
        <span id="hidden_fields">
          {!! csrf_field() !!}
          <input type="hidden" name="m_id" value="{{ $modifier->m_id }}">
        </span>

        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> Modifier Information is saved successfully!
        </div>

        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Modifier Name <span class="required"></span></label>
              <input type="text" name="m_modifier_name" id="M_MODIFIER_NAME" class="form-control" required maxlength="255" value="{{ $modifier->m_modifier_name }}" />
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Item <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="M_ITEM_ID" name="m_item_id">
                <option value="0">-- Select Item --</option>
                @foreach($lst_items as $index => $item_info)
                <option value="{{ $item_info->p_id }}" {{ $modifier->m_item_id == $item_info->p_id ? 'selected' : '' }}>{{ $item_info->p_product_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-12">
            <label class="form-label fw-bold">Modifier Description</label>
            <textarea id="M_MODIFIER_DESCRIPTION" name="m_modifier_description" class="form-control" rows="6" placeholder="Enter description...">{{ $modifier->m_modifier_description }}</textarea>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Unit <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="M_UNIT_ID" name="m_unit_id">
                <option value="0">-- Select Unit --</option>
                @foreach($lst_units as $index => $unit_info)
                <option value="{{ $unit_info->su_id }}" {{ $modifier->m_unit_id == $unit_info->su_id ? 'selected' : '' }}>{{ $unit_info->su_unit_code }} - {{ $unit_info->su_unit_label }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Currency <span class="required"></span></label>
              <select class="form-select form-control" data-control="select2" id="M_CURRENCY_ID" name="m_currency_id">
                <option value="0">-- Select Currency --</option>
                @foreach($lst_currencies as $index => $currency_info)
                <option value="{{ $currency_info->cc_id }}" {{ $modifier->m_currency_id == $currency_info->cc_id ? 'selected' : '' }}>{{ $currency_info->cc_currency_code }} - {{ $currency_info->cc_currency_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="row">

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Quantity <span class="required"></span></label>
              <input type="text" name="m_quantity" id="M_QUANTITY" class="form-control" required placeholder="Enter Quantity" value="{{ $modifier->m_quantity }}" />
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Cost <span class="required"></span></label>
              <input type="text" name="m_cost_modifier" id="M_COST_MODIFIER" class="form-control" required value="{{ $modifier->m_cost_modifier }}" />
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Price <span class="required"></span></label>
              <input type="text" name="m_price_modifier" id="M_PRICE_MODIFIER" class="form-control" required value="{{ $modifier->m_price_modifier }}" />
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <br />
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="m_is_active" id="M_IS_ACTIVE" value="1" {{ $modifier->m_is_active ? 'checked' : '' }} />
                <span class="form-check-label fw-semibold text-muted">
                  Modifier Active
                </span>
              </label>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <br />
                <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input" type="checkbox" name="m_is_required" id="M_IS_REQUIRED" value="1" {{ $modifier->m_is_required ? 'checked' : '' }} />
                  <span class="form-check-label fw-semibold text-muted">
                    Modifier Required?
                  </span>
                </label>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <br />
                <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input" type="checkbox" name="m_is_single" id="M_IS_SINGLE" value="1" {{ $modifier->m_is_single ? 'checked' : '' }} />
                  <span class="form-check-label fw-semibold text-muted">
                    Modifier Single
                  </span>
                </label>
              </div>

            </div>
          </div>
        </div>

        <div class="col-md-12 col-xs-12">
          <div class="d-flex justify-content-end">
            <button type="submit" name="btn_save_modifiers" id="BTN_SAVE_MODIFIER" class="btn btn-info me-2">Save</button>
            <button type="button" id="BACK_FORM" name="back_form" class="btn btn-secondary">Back</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
