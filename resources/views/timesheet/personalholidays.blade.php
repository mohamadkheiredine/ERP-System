<?php
/***********************************************************
personalholidays.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Report for Personal Holidays for loggedin user
***********************************************************/
?>


@extends('layouts.layout',['page_title' => "Personal Holidays"])

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
<script type="text/javascript">
$(function(){
	
})
</script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Personal Holidays
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
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-share"></i>
													<span class="m-nav__link-text">
														Print
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-chat-1"></i>
													<span class="m-nav__link-text">
														Export As CSV
													</span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-multimedia-2"></i>
													<span class="m-nav__link-text">
														Inser Default Holiday
													</span>
												</a>
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
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">

						</div>
						<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
                             <div class="d-md-none m--margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<table class="table">
    				<thead>
    					<tr> 
    						<th align="center">Holiday Date</th>
    						<th align="center">Holiday Status</th> 
    					</tr>
    				</thead>
    				<tbody  class="LstHolidaysGrid">
    					@foreach($personal_holidays as $index => $ch_info)
                        <tr>
                           <td align="center">{{ $ch_info->ph_holiday_date }}</td>
                           <td style="background-color:{{ ( $ch_info->ph_holiday_date < date("Y-m-d") ) ? '#83ffb5' : '#ff6f6f' }}" align="center">{{ ( $ch_info->ph_holiday_date < date("Y-m-d") ) ? "taken" : "Pending" }}</td>
                        </tr>
                        @endforeach
    				</tbody>
    			</table>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
</div>
@endsection