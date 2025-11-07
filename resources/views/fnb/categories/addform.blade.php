@extends('layouts.layout', ['page_title' => "Menu Categories Management"])

@section('themes')
<style>
  th {
    cursor: pointer;
  }

  #ModelPopUp {
    width: 800px;
  }

  .thumbnail {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 5px;
  }

</style>
@endsection

@section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/fnb-category.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/category/saveCategory.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Add New Category</h3>
  </div>

  <div class="card-body">
    <form name="frm_save_category" id="FORM_SAVE_CATEGORY" enctype="multipart/form-data" method="POST">
      {!! csrf_field() !!}
      <div class="alert alert-success" style="display:none">
        <strong>Success!</strong> Category saved successfully!
      </div>
      <div class="alert alert-danger" style="display:none">
        <strong>Error!</strong> Please correct the highlighted fields.
      </div>
      <div class="row">
        <div class="col-md-12" align="left">
          <label>Profile Picture </label>
        </div>
        <div class="col-md-4">
          <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new" style="width: 200px; height: 150px;">
              <img id="MC_PROFILE_PIC" height="120" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
            <div class="PROFILE_PREVIEW fileinput-exists" style="max-width: 200px; max-height: 150px;"> </div>

          </div>
        </div>
        <div class="col-md-8">
          <div class="clearfix margin-top-10">
            <div>
              <span class="btn default btn-file" style="text-align: left;">
                <span class="fileinput-new"> Select image </span><br />
                <input type="file" name="mc_avatar_pic" id="MC_AVATAR_PIC" />
              </span>
            </div>
            <br>
            <span class="label label-danger"> NOTE! </span><br><br>
            <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
          <input type="text" name="mc_category_name" id="MC_CATEGORY_NAME" class="form-control" maxlength="100" required />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">Active Status</label><br />
          <div class="form-check form-switch form-check-custom form-check-solid mt-2">
            <input class="form-check-input" type="checkbox" name="mc_is_active" id="MC_IS_ACTIVE" value="1" checked />
            <span class="form-check-label fw-semibold text-muted">Category Active</span>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-12">
          <label class="form-label fw-bold">Category Description</label>
          <textarea id="MC_CATEGORY_DESCRIPTION" name="mc_category_description" class="form-control" rows="6" placeholder="Enter description..."></textarea>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12 text-end">
          <button type="submit" id="BTN_SAVE_CATEGORY" class="btn btn-info">Save</button>
          <button type="button" id="BACK_FORM" class="btn btn-secondary">Back</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
