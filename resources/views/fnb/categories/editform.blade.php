<?php
{
    $image_src_url  = url('/') . "/" . Config::get('constants.CATEGORIES_PATH')
        . $fnb_categories->mc_profile_base_src
        . $fnb_categories->mc_profile_file_name . "."
        . $fnb_categories->mc_profile_extension;

    $image_src_path = public_path() . "/" . Config::get('constants.CATEGORIES_PATH')
        . $fnb_categories->mc_profile_base_src
        . $fnb_categories->mc_profile_file_name . "."
        . $fnb_categories->mc_profile_extension;

    if (!empty($fnb_categories->mc_profile_base_src) && file_exists($image_src_path)) {
        $img_src = $image_src_url;
    } else {
        $img_src = url('images/NoImageAvailable.jpg');
    }
}
?>


@extends('layouts.layout',['page_title' => "Fnb Categories Management" ])

@section('themes')
<style>
  th { cursor: pointer; }
  #ModelPopUp { width: 800px; }
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
    <h3 class="card-title">Edit Category</h3>
  </div>

  <div class="card-body">
    <form name="frm_save_category" id="FORM_SAVE_CATEGORY" enctype="multipart/form-data" method="POST">
      {!! csrf_field() !!}
      <input type="hidden" name="mc_id" value="{{ $fnb_categories->mc_id }}" />

      <div class="alert alert-success" style="display:none">
        <strong>Success!</strong> Category saved successfully!
      </div>
      <div class="alert alert-danger" style="display:none">
        <strong>Error!</strong> Please correct the highlighted fields.
      </div>

      {{-- Profile Picture --}}
      <div class="row">
        <div class="col-md-12" align="left">
          <label>Profile Picture</label>
        </div>
        <div class="col-md-4">
          <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new" style="width: 200px; height: 150px;">
              <img id="MC_PROFILE_PIC" height="120" src="{{ $img_src }}" alt="Category Image" />
            </div>
            <div class="PROFILE_PREVIEW fileinput-exists" style="max-width: 200px; max-height: 150px;"></div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="clearfix margin-top-10">
            <div>
              <span class="btn default btn-file" style="text-align: left;">
                <span class="fileinput-new">Select image</span><br />
                <input type="file" name="mc_avatar_pic" id="MC_AVATAR_PIC" />
              </span>
            </div>
            <br>
            <span class="label label-danger">NOTE!</span><br><br>
            <span>Attached image thumbnail is supported in latest Firefox, Chrome, Opera, Safari, and Edge.</span>
          </div>
        </div>
      </div>

      <div class="row mb-4 mt-4">
        <div class="col-md-6">
          <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
          <input type="text" name="mc_category_name" id="MC_CATEGORY_NAME"
                 class="form-control" maxlength="100"
                 value="{{ old('mc_category_name', $fnb_categories->mc_category_name) }}" required />
        </div>

        <div class="col-md-6">
          <label class="form-label fw-bold">Active Status</label><br />
          <div class="form-check form-switch form-check-custom form-check-solid mt-2">
            <input class="form-check-input" type="checkbox"
                   name="mc_is_active" id="MC_IS_ACTIVE"
                   value="1"
                   {{ $fnb_categories->mc_is_active == 1 ? 'checked' : '' }} />
            <span class="form-check-label fw-semibold text-muted">Category Active</span>
          </div>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-12">
          <label class="form-label fw-bold">Category Description</label>
          <textarea id="MC_CATEGORY_DESCRIPTION" name="mc_category_description"
                    class="form-control" rows="6">{{ old('mc_category_description', $fnb_categories->mc_category_description) }}</textarea>
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
