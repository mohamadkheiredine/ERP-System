<?php
/***********************************************************
addemptype.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 3, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>



@extends('layouts.layout',['page_title' => "Employment Type Management > Add New Employment Type"])

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
<script type="text/javascript" src="{{ url('js/modules/emptype.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/system/saveemptype.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Job Roles</h3>
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
    		<form name="frm_save_emptype" id="FORM_SAVE_EMPTYPE">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Employment Type Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
            			 
                    <div class="row">
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Employment Type<span class="required"> * </span></label>
                                <input type="text" name="et_type" id="ET_TYPE" class="form-control" required="required" maxlength="100"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Min Working Hours<span class="required"> * </span></label>
                                <input type="number" step="0.5" name="et_min_working_hours" id="ET_MIN_WORKING_HOURS" class="form-control" required="required" max="18"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Order<span class="required"> * </span></label>
                                <input type="number" step="1" name="et_order" id="ET_ORDER" class="form-control" required="required" max="18"  value="" />
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_emptype" id="BTN_SAVE_EMPTYPE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>
    


@endsection