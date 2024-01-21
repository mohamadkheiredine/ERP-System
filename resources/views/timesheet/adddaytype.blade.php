<?php
/***********************************************************
adddaytype.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 5, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Day Types Management > Add New Day Type"])

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
<script type="text/javascript" src="{{ url('js/modules/daytypes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/savedaytypes.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Add Day Type</h3>
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
    <form name="frm_save_daytype" id="FORM_SAVE_DAYTYPE">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Day Type Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Day Type <span class="required"> * </span></label>
                                    <input type="text" name="dt_day_type" id="DT_DAY_TYPE" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Color <span class="required"> * </span></label>
                                <input type="color" name="dt_day_color" id="DT_DAY_COLOR" class="form-control" required="required"  value="" />
                            </div>
                        </div>
                         <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label"> working hours <span class="required"> * </span></label>
                                <input type="number" step="0.1" min="0" max="12" step="0.1" name="dt_working_hours" id="DT_WORKING_HOURS" class="form-control" required="required"  value="1" />
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label class="control-label">Show From Employees</label><br/>
                                <span class="m-switch m-switch--lg">
									<label>
										<input type="checkbox" name="dt_show_for_employee" id="DT_SHOW_FOR_EMPLOYEE" class="form-control" value="1" />
										<span></span>
									</label>
								</span>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_daytype" id="BTN_SAVE_DAYTYPE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

@endsection