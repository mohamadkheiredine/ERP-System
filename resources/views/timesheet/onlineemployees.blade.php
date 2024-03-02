<?php
/***********************************************************
onlineemployees.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

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
<script type="text/javascript" src="{{ url('js/libraries/timesheet/onlineemployees.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Online Employees</h3>
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
    		<!--begin: Search Form -->
        		<div class="col-md-12">
        			<div class="row align-items-center">
        				<div class="col-xl-8 order-2 order-xl-1">
        					<div class="form-group align-items-center">
        						<div class="col-md-4">
        						</div>
        						<div class="col-md-4">
                                   <br/>
        						</div>
        						<div class="col-md-4">
                                    <br/>
        						</div>
        					</div>
        				</div>
        				<div class="col-xl-4 order-1 order-xl-2 align-right">
        				
        				</div>
        			</div>
        		</div>
        		<!--end: Search Form -->
                  <!--begin: Datatable -->
        		<div class="m_datatable" id="LstOnlineEmployees">
        
        		</div>
        		<!--end: Datatable -->
    </div>
 </div>
@endsection