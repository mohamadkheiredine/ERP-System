<?php
/***********************************************************
addpergroup.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


$company_country = session("company_country");
?>
@extends('layouts.layout',['page_title' => "Personalized Groups > Add New Group"])

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
<script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/personalizedgroups.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/savepersgroups.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add New Personalized Group</h3>
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
             <form name="frm_save_group" id="FORM_SAVE_GROUP">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Group Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Group Code <span class="required"> * </span> </label>
                                    <input type="text" name="pg_group_code" id="PG_GROUP_CODE" class="form-control"  maxlength="15"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Label <span class="required"> * </span></label>
                                <input type="text" name="pg_group_label" id="PG_GROUP_LABEL" class="form-control" required="required" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Calculated</label>
                                <ul class="LstradioBtn">
                                	<li><input type="radio" name="pg_group_calculated"   value="1"  /><label>&nbsp;YES</label></li>
                                	<li><input type="radio" name="pg_group_calculated"   value="0"  /><label>&nbsp;NO</label></li>
                                </ul>
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Formula</label>
                                <input type="text" name="pg_group_formula" id="PG_GROUP_FORMULA" class="form-control" maxlength="255"  value="" />
                            </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Position </label>
                                    <input type="number" step="1" min="0" max="1000" name="pg_group_position" id="PG_GROUP_POSITION" class="form-control"  value="" />
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Is Active </label><br/>
                                    <span class="m-switch m-switch--icon m-switch--info">
            							<label>
            								<input type="checkbox"  name="pg_is_active" value="1" />
            								<span class="ActualSwitch"></span>
            							</label>
            						</span>
                                </div>
                        </div> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Group Comment </label>
                                <textarea class="form-control" id="PG_GROUP_COMMENT" name="pg_group_comment" style="width:100%;height:250px;resize:none" ></textarea>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_group" id="BTN_SAVE_GROUP"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection