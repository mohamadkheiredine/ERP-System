<?php
/***********************************************************
todaysapt
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 9, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Call Center Management"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
.SwitchDisplay i{
    font-size: 24px;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/callapt.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/dailyappointment.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Today's Appointments</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
                    <li><a class="dropdown-item DownloadAppointment" data-action_type="DOWNLOAD_APPOINTMENTS" href="#">Download Appointments</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
            <span id="hidden_fields">
                <input type="hidden" name="display_type" value="list" />
            </span>
             <div class="row">
                <div style="text-align:right" class='col-md-4'>
                    <input type="text" name="ca_appointment_date" class="form-control" id="CA_APPOINTMENT_DATE" value="{{ date('Y-m-d') }}" />
                </div>
                 <div style="text-align:right" class='col-md-4'></div>
                 <div style="text-align:right" class='col-md-4'></div>
            </div> 
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:right" class='col-md-12'>
                    <a href="#" data-display_type="list" class="SwitchDisplay"><i class="fa-solid fa-list"></i></a>
                    <a href="#" data-display_type="calendar" class="SwitchDisplay"><i class="fa-solid fa-calendar"></i></a>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:center" id="LstAppointments" class='col-md-12'>
                </div>
            </div> 
		<div class="row">
			<div class="col-xl-8 order-1 order-xl-1 align-right"></div>
			<div class="col-xl-2 order-2 order-xl-2 align-right">
				 
			</div>
			<div class="col-xl-2 order-3 order-xl-3 align-right">
				<div class="row">
                                    <div class="col-md-12" align="right">
                                         <button type="button" name="btn_new_apt" id="BTN_NEW_APT"  class="btn btn-info">New Apt</button>
                                    </div>
                                </div>
			</div>
		</div>
	</div>
</div>

@endsection