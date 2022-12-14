<?php
/***********************************************************
editdaytimesheet.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 18, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Edit Timesheet
***********************************************************/
 
?>

<div class="row">
	<div class="col-md-4">
		<div class="form-group">
            <label class="control-label"> Day Type </label><br/>
            <select class="bs-select form-control" name="ts_day_type" id="TS_DAY_TYPE"  style="width:100%" data-actions-box="true">
                    <option value=""> -- Day Type -- </option>
                    @foreach($list_days_type as $key => $day_type)
                            <option {{ ( isset( $today_timesheet[0]['fk_date_type']) && $today_timesheet[0]['fk_date_type'] == $day_type->dt_id ) ? "selected" : "" }} value="{{ $day_type->dt_id }}">{{ $day_type->dt_day_type }}</option>
                    @endforeach
            </select>
        </div>
	</div>
	<div class="col-md-8">
	
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table m-table m-table--head-bg-brand">
			<thead>
				<tr>
					<th style="width:20%">Checkin Time</th>
					<th style="width:20%">Checkout Time </th>
					<th style="width:60%">note</th>
				</tr>
			</thead>
			<tbody>
				@foreach( $today_timesheet as $index => $ts_info )
				<tr>
					<td style="width:20%">
						<input type="hidden" name="ts_id[]" value="{{ $ts_info->ts_id }}" />
						<input type="text" class="form-control timesheet" readonly placeholder="Select time" name="ts_timesheet_checkin[]" value="{{ $ts_info->ts_timesheet_checkin }}"  />
					</td>
					<td style="width:20%">
						<input type="text" class="form-control timesheet" readonly placeholder="Select time" name="ts_timesheet_checkout[]" value="{{ $ts_info->ts_timesheet_checkout }}"  /> 
					</td>
					<td style="width:60%"><input type="text" class="form-control" name="ts_record_note[]" value="{{ $ts_info->ts_record_note }}"  /></td>
				</tr>
				@endforeach
				@if(count($today_timesheet) == 0)
				<tr>
					<td style="width:20%">
						<input type="hidden" name="ts_id[]" value="0" />
    					<input type="text" class="form-control timesheet" readonly placeholder="Select time" name="ts_timesheet_checkin[]" value=""  />
					</td>
					<td style="width:20%">
						<input type="text" class="form-control timesheet" readonly placeholder="Select time" name="ts_timesheet_checkout[]" value=""  /> 
					</td>
					<td style="width:60%"><input type="text" class="form-control" name="ts_record_note[]" value=""  /></td>
				</tr>
				<tr>
					<td colspan="3" align="right">
						<a href="#" id="NEW_ROW"><i class="fa fa-plus-circle"></i></a>
					</td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-md-12" align="right">
		<button type="button" name="btn_save_timesheet" id="BTN_SAVE_TIMESHEET" class="btn btn-primary" >Save Timesheet</button>
	</div>
</div>