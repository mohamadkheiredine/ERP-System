<?php
/***********************************************************
addcategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Add Product Category
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Product Categories Management"])

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
<script type="text/javascript" src="{{ url('js/modules/fnb-category.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/fnb/category/saveCategory.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
  <div class="card-header">
    <h3 class="card-title">Add New Category</h3>
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
    <form name="frm_save_category" id="FORM_SAVE_CATEGORY">
      <div class="form-body">
        <span id="hidden_fields">
          <div class="form-group">
            {!! csrf_field() !!}
          </div>
        </span>
        <div class="alert alert-success" style="display:none">
          <strong>Success!</strong> Product Category Information is saved successfully!
        </div>
        <div class="alert alert-danger" style="display:none">
          <strong>Error!</strong> You have some form errors. Please check below.
        </div>
        <div class="row">
          <div class="col-md-12" align="left">
            <label>Profile Picture </label>
          </div>
          <div class="col-md-4">
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                <img id="AVATAR_PIC" height="120" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>

            </div>
          </div>
          <div class="col-md-8">
            <div class="clearfix margin-top-10">
              <div>
                <span class="btn default btn-file" style="text-align: left;">
                  <span class="fileinput-new"> Select image </span><br />
                  <input type="file" name="pc_avatar_pic" id="PC_AVATAR_PIC" /> </span>
              </div>
              <br>
              <span class="label label-danger"> NOTE! </span><br><br>
              <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
          </div>
          <div class="col-md-4" style="display: none;">
            <div class="form-group">
              <label class="control-label"> Category Code <span class="required"> * </span></label>
              <input type="text" name=" pc_cat_ref" id="PC_CAT_REF" class="form-control" maxlength="100" value="" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label">Category Name <span class="required"> * </span></label>
              <input type="text" name="mc_category_name" id="MC_CATEGORY_NAME" class="form-control" required="required" maxlength="100" value="" />
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label"> Category Description</label><br />
              <textarea style="width:100%;height:250px;resize:none" id="MC_CATEGORY_DESCRIPTION" class="form-control" name="mc_category_description" cols=""></textarea>
            </div>
          </div>

          <div class="form-group">
          <br />
          <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" name="mc_is_active" id="MC_IS_ACTIVE" value="1" />
            <span class="form-check-label fw-semibold text-muted">
              Category Active
            </span>
          </label>
        </div>


        </div>
        <div class="row" style="height:5px;"></div>
        <div class="row">
          <div class="col-md-9"></div>
          <div class="col-md-3" align="right">
            <button type="submit" name="btn_save_category" id="BTN_SAVE_CATEGORY" class="btn btn-info">Save</button>
            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection
