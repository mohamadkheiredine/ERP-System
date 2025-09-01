<?php
/***********************************************************
types.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 17, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Projects Management"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
</style>
@endsection
@section('plugins')
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/projecttypes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/pmp/saveprojecttype.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Project Types Management</h3>
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
             <form name="frm_save_types" id="FORM_SAVE_TYPES">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Project Types Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Type Title <span class="required"> * </span></label>
                                    <input type="text" name="pt_type_name" id="PT_TYPE_NAME" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Type Color</label>
                                <input type="color" name="pt_type_color" id="PT_TYPE_COLOR" class="form-control" required="required" maxlength="8"  value="" />
                            </div>
                        </div>
                         <div class="col-md-12">
                             <div class="form-group">
                                <label class="control-label">Type Description</label>
								<textarea name="pt_type_description" id="PT_TYPE_DESCRIPTION" class="form-control" style="width:100%;height:250px;"></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_type" id="BTN_SAVE_TYPE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>
@endsection
