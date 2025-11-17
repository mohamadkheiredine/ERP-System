@extends('layouts.layout',['page_title' => "Receipes Management"])

@section('themes')
<style>
  th {
    cursor: pointer;
  }

  #ModelPopUp {
    width: 800px;
  }

  .recipe-card.selected {
    border: 2px solid #fd7e14 !important;
    background-color: #fff7ef !important;
  }

  .recipe-card:hover {
    background-color: #fff3e3;
  }

</style>
@endsection

@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/fnb-receipes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/receipes/receipes.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-md">
  <div class="card-header">
    <h3 class="card-title">Receipes</h3>
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
      <input type="hidden" id="SELECTED_RECEIPE_ID" name="selected_receipe_id">
    </span>
    <div class="container-fluid py-4">
      <div class="row">

        <!-- LEFT SIDEBAR ONLY -->
        <div class="col-3">
          <div class="card shadow-sm">
            <div class="card-body">

              <input type="text" class="form-control mb-3" placeholder="Search recipes…" id="GENERAL_SEARCH" name="general_search">

              <div id="LST_RECIPES">
                <!-- AJAX injected recipe cards -->
              </div>

            </div>
          </div>
        </div>

        <!-- RIGHT CONTENT (HIDDEN UNTIL ONE CARD CLICKED) -->
        <div class="col-9" id="RECIPE_CONTENT_WRAPPER">
        </div>

      </div>

    </div>
  </div>
</div>
@endsection
