@extends('layouts.layout',['page_title' => "Table Management"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-tables.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/tables/tables.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">FNB TABLES</h3>
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
    <span id="hidden_fields">
      <input type="hidden" name="page_number" value="1" />
    </span>
    <!--begin: Search Form -->
    <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
      <div class="row align-items-center">
        <div class="col-xl-8 order-2 order-xl-1">
          <div class="form-group m-form__group">
            <div class="row g-3 align-items-center">
              <!-- Search input -->
              <div class="col-md-6">
                <div class="position-relative">
                  <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-3"></i>
                  <input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" placeholder="Search" />
                </div>
              </div>

              <!-- Select dropdown -->
              <div class="col-md-6">
                <select class="form-select form-control" data-control="select2" id="FL_ID" name="fl_id">
                  <option value="0">-- Select Floor --</option>
                  @foreach($lst_floors as $index => $floor_info)
                  <option value="{{ $floor_info->fl_id }}">{{ $floor_info->fl_floor_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Right-side button -->
        <div class="col-xl-4 order-1 order-xl-2 text-end">
          <a href="{{ url('fnb/tables/addform') }}" class="btn btn-info">
            <i class="fas fa-user me-1"></i> New Table
          </a>
        </div>
      </div>
    </div>
    <div class="col-md-12">&nbsp;</div>

    <!--end: Search Form -->
    <div class="col-md-12">

      <table class="table table-bordered table-hover table-responsive">
          <table class="table table-row-dashed table-row-gray-300 gy-7">
              <thead>
              <tr class="fw-bold fs-6 text-gray-800">
            <th style="width:4px;white-space: nowrap;" title="Id"></th>
            <th style="width:4px;white-space: nowrap;" title="Id">ID</th>
            <th title="Table Label">Label</th>
            <th title="Floor Name">Floor</th>
            <th title="Capacity">Capacity</th>
            <th style="width:4px;white-space: nowrap;" title="#">edit</th>
            <th style="width:4px;white-space: nowrap;" title="#">Delete</th>
          </tr>
        </thead>
        <tbody class="LstTablesGrid" id="LstTablesGrid">
        </tbody>
      </table>
    </div>
    <div class="row">
      <div class="col-md-10" align="left">
        <ul id="TablesPagination" class="pagination-sm"></ul>
      </div>
      <div class="col-md-2" align="right"></div>
    </div>
    <div class="row">
      <div class="col-md-8"></div>
      <div class="col-md-4" align="right">
        <a href="{{ url('fnb/tables/addform') }}" class="btn btn-info">
          <span>
            <i class="fas fa-user"></i>
            <span>
              New Table
            </span>
          </span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
