@extends('layouts.layout',['page_title' => "Products Menu Catgegory Management"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-itemscategory.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/category/itemscategory.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Products</h3>
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
        <div class="col-xl-8 order-2 order-xl-1">
          <div class="form-group m-form__group row align-items-center">
            <div class="col-md-4">
              <div class="d-flex flex-wrap align-items-center gap-3">
                <!-- Search input -->
                <div class="position-relative w-md-400px flex-grow-1">
                  <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" placeholder="Search" />
                </div>

                <!-- Company select -->
                <div>
                  <select class="form-select form-select-solid w-auto" data-control="select2" id="FI_COMPANY_ID" name="fi_company_name">
                    <option value="0">-- Select Company --</option>
                    @foreach($lst_companies as $index => $company_info)
                    <option value="{{ $company_info->cd_id }}">
                      {{ $company_info->cd_company_name }}
                    </option>
                    @endforeach
                  </select>
                </div>

                <!-- Kitchen select -->
                <div>
                  <select class="form-select form-select-solid w-auto" data-control="select2" id="FI_KITCHEN_ID" name="fi_kitchen_name">
                    <option value="0">-- Select Kitchen --</option>
                    @foreach($lst_kitchens as $index => $kitchen_info)
                    <option value="{{ $kitchen_info->ks_id }}">
                      {{ $kitchen_info->ks_name }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <br />
              </div>
              <div class="col-md-4">
                <br />
              </div>
            </div>
          </div>
          <div class="col-xl-4 order-1 order-xl-2 align-right">

          </div>
        </div>
      </div>
      <!--end: Search Form -->
      <!--begin: Datatable -->
      <div id="LstProductsMain" class="table-responsive">
        <table class="table" id="html_table" width="100%">
          <thead>
            <tr>
              <th title="#">#</th>
              <th title="Id"> ID </th>
              <th title="Name"> Product Name </th>
              <th title="edit"> edit </th>
            </tr>
          </thead>
          <tbody id="LstProducts"></tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-md-10" align="left">
          <ul id="ProductsPagination" class="pagination-sm"></ul>
        </div>
        <div class="col-md-2" align="right"></div>
      </div>
      <!--end: Datatable -->
      <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4" align="right">
          <a href="{{ url('fnb/categories/listitems/additem') }}" class="btn btn-info">
            <span>
              <i class="fas fa-user"></i>
              <span>
                New Product Item
              </span>
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>

  @endsection
