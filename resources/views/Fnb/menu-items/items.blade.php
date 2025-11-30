@extends('layouts.layout',['page_title' => "Menu Items"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-items.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/menu-items/items.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Items</h3>
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
    <!--begin: Search Form -->
    <span id="hidden_fields">
      <input type="hidden" name='page_number' value="1" />
    </span>
    <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
      <div class="row align-items-center">

        <div class="col-xl-12">
          <div class="form-group m-form__group">

            <div class="row g-3 align-items-center">

              <!-- Search input -->
              <div class="col-md-3">
                <div class="position-relative">
                  <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" placeholder="Search" />
                </div>
              </div>

              <!-- Select Company -->
              <div class="col-md-3">
                <select class="form-select form-select-solid" data-control="select2" id="FI_COMPANY_ID" name="fi_company_name">
                  <option value="0">-- Select Company --</option>
                  @foreach($lst_companies as $index => $company_info)
                    <option value="{{ $company_info->cd_id }}">
                        {{ $company_info->cd_company_name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Select Kitchen -->
              <div class="col-md-3">
                <select class="form-select form-select-solid" data-control="select2" id="FI_KITCHEN_ID" name="fi_kitchen_name">
                  <option value="0">-- Select Kitchen --</option>
                    @foreach($lst_kitchens as $index => $kitchen_info)
                        <option value="{{ $kitchen_info->ks_id }}">
                            {{ $kitchen_info->ks_name }}
                        </option>
                    @endforeach
                </select>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!--end: Search Form -->
    <!--begin: Datatable -->
    <div id="LstItemsMain" class="table-responsive mt-10">
        <table class="table table-rounded table-striped border gy-7 gs-7">
            <thead>
            <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            <th title="#" style="width: 2px">#</th>
            <th title="Id" style="width: 2px"> ID </th>
            <th title="Name"> Item Name </th>
                <th title="Price"> Price </th>
            <th title="edit" style="width: 2px"> edit </th>
            <th title="delete" style="width: 2px">Delete</th>
          </tr>
        </thead>
        <tbody id="LstItems"></tbody>
      </table>
    </div>
    <div class="row">
      <div class="col-md-10" align="left">
        <ul id="ItemsPagination" class="pagination-sm"></ul>
      </div>
      <div class="col-md-2" align="right"></div>
    </div>
    <!--end: Datatable -->
    <div class="row">
      <div class="col-md-8"></div>
      <div class="col-md-4" align="right">
        <a href="{{ url('fnb/menuitems/additem') }}" class="btn btn-info">
          <span>
            <i class="fas fa-user"></i>
            <span>
              New Item
            </span>
          </span>
        </a>
      </div>
    </div>
  </div>
</div>

@endsection
