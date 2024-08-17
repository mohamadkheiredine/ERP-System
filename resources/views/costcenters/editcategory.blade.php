<?php 
/***********************************************************
editcategory.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>


@extends('layouts.layout',['page_title' => "Edit Existing Category"])

@section('themes')
<style>
th {
	cursor: pointer;
}

#ModelPopUp {
	width: 800px;
}
</style>
@endsection @section('plugins')
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/cccategories.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/costcenters/savecategory.js') }}"></script>
@endsection @section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Add Category</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
		<form name="frm_save_category" id="FORM_SAVE_CATEGORY">
			<div class="form-body">
				<span id="hidden_fields">
                                    {!! csrf_field() !!} 
                                    <input type="hidden" name="cca_id" value="{{ $category_info->cca_id }}" />
                                </span>
				<div class="alert alert-success" style="display: none">
					<strong>Success!</strong> Category Information is saved successfully!
				</div>
				<div class="alert alert-danger" style="display: none">
					<strong>Error!</strong> You have some form errors. Please check
					below.
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Category <span class="required"> * </span></label>
							<input type="text" name="cca_category_name" id="CCA_CATEGORY_NAME" class="form-control" required="required" maxlength="255" value="{{ $category_info->cca_category_name }}" />
						</div>
					</div>
					<div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Parent Category</label>
                                 <select class="bs-select form-control" name="fk_cca_id" id="FK_CCA_ID" data-actions-box="true">
                                        <option value="">No Parent</option>
                                        @foreach ( $lst_costcenter_categories as $key => $cat_info )
                                                <option {{ $category_info->fk_cca_id == $cat_info->cca_id ? "selected" : "" }} value="{{ $cat_info->cca_id }}">{{ $cat_info->cca_category_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
					<div class="col-md-12">
						<label class="control-label">Description</label><br/>
						<textarea style="width:100%;height:250px;" name="cca_description" id="CCA_DESCRIPTION" class="form-control">{{ $category_info->cca_description }}</textarea>
					</div>
				</div>
				<div class="row" style="height: 5px;"></div>
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
