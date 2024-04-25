<?php
/***********************************************************
timesheet.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to manage timesheet 
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
td{
	font-weight: bold;
	color:white;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/timesheetmanagement.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/timesheet/timesheetmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Timesheet Management</h3>
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
        <div class="row">
		 
                 
                 <div class="col-md-12 table-responsive" id="LstProductsMain">
                     <input type="hidden" name="action" value="checkin" />
									<table class="table table-row-dashed table-row-gray-300 gy-7">
                                		<thead>
                                			<tr class="fw-bold fs-6 text-gray-800">
                                				<tr>
                                                                    <th>day</th>
                                                                    <th>day type</th>
                                                                        <th>checkin time</th>
                                                                        <th>checkout time</th>
                                                                        <th>remaining time</th>
                                                                        <th>total time </th>
                                                                </tr>
                                			</tr>
                                		</thead>
                                    	<tbody  id="TimeSheetManagement"></tbody>
                                    </table>
                                    									
	</div>
	</div>
	<!--end: Datatable -->
 	<div class="row">
 		<div class="col-md-6" align="left">
 		
 		</div>
 		<div class="col-md-6" align="right">
 			<button type="button" name="btn_checkin" id="BTN_CHECKIN" class="btn btn-info">Checkin/Checkout</button>
 		</div>
 	</div>
    </div>
 </div>
 
<div class="modal fade" id="CheckOutInfo" tabindex="-1" role="dialog" aria-labelledby="CheckOutInfoModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="InserItemsModalLabel">
					CheckIn/CheckOut Information
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						&times;
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="frm_checkin_timesheet" id="FRM_CHECKIN_TIMESHEET" method="post"  enctype="multipart/form-data"> 
				    {!! csrf_field() !!}
				    <input type="hidden" name="action" value="checkout" />
				 	<div class="row"> 
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Day Type </label><br/>
                                     <select class="bs-select form-control" name="ts_day_type" id="TS_DAY_TYPE"  style="width:100%" data-actions-box="true">
                                            <option value=""> -- Day Type -- </option>
                                            @foreach($list_days_type as $key => $day_type)
                                                    <option value="{{ $day_type->dt_id }}">{{ $day_type->dt_day_type }}</option>
                                            @endforeach
                                    </select>
                                </div>
				 		</div>
				 		<div class="col-md-12">
				 			  <div class="form-group">
                                    <label class="control-label"> Note </label><br/>
                                    <textarea name="timesheet_note" class="form-control" style="width:100%;height:150px;"></textarea>
                                </div>
				 		</div>
				 		<div class="col-md-6"></div>
				 		<div class="col-md-6">
				 			<button id="BTN_CLOSE" name="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">
            					Close
            				</button>
            				<button type="submit" name="btn_save_checkin" id="BTN_SAVE_CHECKIN" class="btn btn-primary">
            					Confirm
            				</button>
				 		</div>
				 	</div>
				</form>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>
@endsection