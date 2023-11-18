<?php
/***********************************************************
zones.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to control warehouse zones Add/Edit and delete zones
***********************************************************/


?>
<div class="row">
	<div class="col-md-2 col-xs-2"></div>
	<div class="col-md-8 col-xs-8 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>
							#
						</th>
						<th>Label</th>
						<th>Color</th>
						<th>width</th>
						<th>Height</th>
						<th>Length</th>
						<th>Volume</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="LstWarehouseZones">
				@foreach( $warehousezones_obj as $index => $zone_info )
					<tr  data-wz_id="{{ $zone_info->wz_id }}"  bgcolor="{{ $zone_info->wz_zone_color }}">
						<td> {{ $zone_info->wz_id }} </td>
						<td> {{ $zone_info->wz_zone_label }} </td>
						<td> {{ $zone_info->wz_zone_color }} </td>
						<td> {{ $zone_info->wz_zone_width }} </td>
						<td> {{ $zone_info->wz_zone_height }} </td>
						<td> {{ $zone_info->wz_zone_length }} </td>
						<td> {{ $zone_info->wz_zone_volume }} </td>
						<td> <a  href="#"  id="DELETE_ZONE_{{ $zone_info->wz_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="8" ></i></a> </td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="col-md-12" align="right">
				<button type="button" name="btn_create_zone" id="BTN_CREATE_ZONE" class="btn m-btn m-btn--gradient-from-primary m-btn--gradient-to-info"> Create Zone </button>
			</div>
	</div>
	<div class="col-md-2 col-xs-2"></div>
</div>