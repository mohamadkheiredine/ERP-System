<?php
/***********************************************************
transportationemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page of Report for transportation of all employees for seletced years and months
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Timesheet Management"])

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
<script type="text/javascript" src="{{ url('js/modules/timesheetmanagement.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/transportationemployees.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Transportation Employees</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              	              		<li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
                <div class="col-md-12">
                    <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                            <div class="col-md-8">
                                                      <div class="form-group">
                            <label class="control-label"> Date <span class="required"> * </span></label><br/>
                           <input type="text" name="te_date" id="TE_DATE" class="form-control" style="width:100%" value="" />
                        </div>
                                            </div>
                                            <div class="col-md-4">
                                                    <label class="control-label">&nbsp;</label><br/>
                        <button type="button" name="btn_search" class="btn btn-primary"> Search </button>
                                            </div>
                                    </div>
                            </div>
                            <div class="col-xl-4 order-1 order-xl-2 align-right">

                            </div>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
        </div>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered m-table m-table--border-brand m-table--head-bg-brand">
                    <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Transportation Days</th>
                                <th>Total Transportation Fees</th>
                            </tr>
                    </thead>
                    <tbody id="LstTransEmployees">

                    </tbody>
            </table>

            </div>
        </div> 
    </div>
</div>

@endsection