<?php
/***********************************************************
calendarappt.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 7, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "My Calendar"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
</style>

	<link rel="stylesheet" href="{{ url('default/assets/plugins/scheduler/codebase/dhtmlxscheduler_material.css?v=5.2.2') }}" type="text/css" charset="utf-8">
@endsection
@section('plugins')
	<script src="{{ url('default/assets/plugins/scheduler/codebase/dhtmlxscheduler.js?v=5.2.2') }}" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		$(function(){
			 var path = "{{ $calendar_path }}";
	   		  calendar_path = atob(path);
	 
	   			scheduler.init('scheduler_here',Date.now(),"week");
	   			scheduler.load(calendar_path);
		})
	</script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					My Calendar
				</h3>
			</div>
		</div>
		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
						<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
							<i class="la la-ellipsis-h m--font-brand"></i>
						</a>
						<div class="m-dropdown__wrapper">
							<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
							<div class="m-dropdown__inner">
								<div class="m-dropdown__body">
									<div class="m-dropdown__content">
										<ul class="m-nav">
											<li class="m-nav__section m-nav__section--first">
												<span class="m-nav__section-text">
													Quick Actions
												</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="m-portlet__body">
		<div class="row">
			<div class="col-md-12">
				<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:1200px'>
                	<div class="dhx_cal_navline">
                		<div class="dhx_cal_prev_button">&nbsp;</div>
                		<div class="dhx_cal_next_button">&nbsp;</div>
                		<div class="dhx_cal_today_button"></div>
                		<div class="dhx_cal_date"></div>
                		<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                		<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                		<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
                	</div>
                	<div class="dhx_cal_header">
                	</div>
                	<div class="dhx_cal_data">
                	</div>
                </div>
			</div>			
		</div> 
	</div>
</div>
@endsection
