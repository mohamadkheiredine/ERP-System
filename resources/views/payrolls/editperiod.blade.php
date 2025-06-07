<?php
/***********************************************************
editperiod
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>



@extends('layouts.layout',['page_title' => "PayRoll Periods"])

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
    <script type="text/javascript" src="{{ url('js/modules/payrollperiods.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/savepayrollperiod.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Existing PayRoll Period</h3>
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
            <form name="frm_save_period" id="FORM_SAVE_PERIOD">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                         <input type="hidden" name="pp_id" value="{{ $payroll_periods->pp_id  }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> PayRoll Period  Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4" >
                            <div class="form-group">
                                <label class="control-label"> Period Title <span class="required"> * </span></label>
                                <input type="text" name="pp_period_name" id="PP_PERIOD_NAME" class="form-control" maxlength="255"  value="{{ $payroll_periods->pp_period_name  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Status </label>
                                <select  name="pp_frequency" id="PP_FREQUENCY" class="form-control form-select" data-control="select2" data-placeholder="Select Frequency">
                                    <option value="">Select Frequency</option>
                                    <option {{ $payroll_periods->pp_frequency == "weekly" ? "selected" : ""  }} value="weekly">weekly</option>
                                    <option {{ $payroll_periods->pp_frequency == "bi-weekly" ? "selected" : ""  }} value="bi-weekly">bi-weekly</option>
                                    <option {{ $payroll_periods->pp_frequency == "monthly" ? "selected" : ""  }} value="monthly">monthly</option>
                                    <option {{ $payroll_periods->pp_frequency == "quarterly" ? "selected" : ""  }} value="quarterly">quarterly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Status </label>
                                <select  name="pp_status" id="PP_STATUS" class="form-control form-select" data-control="select2" data-placeholder="Select Status">
                                    <option value="">Select Status</option>
                                    <option {{ $payroll_periods->pp_status == "open" ? "selected" : ""  }} value="open">open</option>
                                    <option {{ $payroll_periods->pp_status == "closed" ? "selected" : ""  }} value="closed">closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Start Date <span class="required"> * </span></label>
                                <input type="text" name="pp_start_date" id="PP_START_DATE" class="form-control" required="required" maxlength="20"  value="{{ $payroll_periods->pp_start_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">End Date <span class="required"> * </span></label>
                                <input type="text" name="pp_end_date" id="PP_END_DATE" class="form-control" required="required" maxlength="20"  value="{{ $payroll_periods->pp_end_date }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_period" id="BTN_SAVE_PERIOD"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
