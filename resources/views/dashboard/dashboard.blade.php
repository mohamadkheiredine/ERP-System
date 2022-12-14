<?php
/***********************************************************
dashboard.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 5, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Dashboard"])

@section('plugins')
<script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
<script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
    <script src="{{ url('admin/assets/pages/scripts/dashboard.js') }}" type="text/javascript"></script>
		<script src="{{ url('default/assets/app/js/dashboard.js') }}" type="text/javascript"></script>
		

@endsection
@section('themes')
	<link href="{{ url('admin/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ url('admin/assets/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<style>
            .amcharts-export-menu.amcharts-export-menu-top-right.amExportButton {
                display: none;
            }
        </style>
@endsection

@section('content')
<div class="row">
	<div class="col-md-12">
		@if($count_rates == 0 ) 
		<div class="alert alert-danger" role="alert">
			<strong>Warning !</strong>
			Please Add the Daily Exchange Rate Before begin your operations
		</div>
		@endif
	</div>
</div>
 <div class="row">
 	<div class="col-md-12">
 		<div class="m-portlet">
							<div class="m-portlet__body  m-portlet__body--no-padding">
								<div class="row m-row--no-padding m-row--col-separator-xl">
									<div class="col-xl-4">
										<!--begin:: Widgets/Stats2-1 -->
										<div class="m-widget1">
											<div class="m-widget1__item">
												<div class="row m-row--no-padding align-items-center">
													<div class="col">
														<h3 class="m-widget1__title">
															Customers
														</h3>
														<span class="m-widget1__desc">
															Point of Sales Customers
														</span>
													</div>
													<div class="col m--align-right">
														<span class="m-widget1__number m--font-brand">
															{{ $count_customers > 0 ? "+" . $count_customers : "0" }}
														</span>
													</div>
												</div>
											</div>
											<div class="m-widget1__item">
												<div class="row m-row--no-padding align-items-center">
													<div class="col">
														<h3 class="m-widget1__title">
															Orders
														</h3>
														<span class="m-widget1__desc">
															Customer Orders
														</span>
													</div>
													<div class="col m--align-right">
														<span class="m-widget1__number m--font-danger">
															{{ $count_orders > 0 ? $count_orders : "0" }}
														</span>
													</div>
												</div>
											</div>
											<div class="m-widget1__item">
												<div class="row m-row--no-padding align-items-center">
													<div class="col">
														<h3 class="m-widget1__title">
															Invoices
														</h3>
														<span class="m-widget1__desc">
															Total Invoices issued this week
														</span>
													</div>
													<div class="col m--align-right">
														<span class="m-widget1__number m--font-success">
															{{ $count_invoices > 0 ? $count_invoices : "0" }}
														</span>
													</div>
												</div>
											</div>
										</div>
										<!--end:: Widgets/Stats2-1 -->
									</div>
									<div class="col-xl-4">
										<!--begin:: Widgets/Daily Sales-->
										<div class="m-widget14">
											<div class="m-widget14__header m--margin-bottom-30">
												<h3 class="m-widget14__title">
													Daily Sales
												</h3>
												<span class="m-widget14__desc">
													Sales Volum showed by Day in the Current Week 
												</span>
											</div>
											<div class="m-widget14__chart" style="height:120px;">
												<canvas  id="m_chart_daily_sales"></canvas>
											</div>
										</div>
										<!--end:: Widgets/Daily Sales-->
									</div>
									<div class="col-xl-4">
										<!--begin:: Widgets/Profit Share-->
										<div class="m-widget14">
											<div class="m-widget14__header">
												<h3 class="m-widget14__title">
													Profit Share
												</h3>
												<span class="m-widget14__desc">
													Profit Share between customers
												</span>
											</div>
											<div class="row  align-items-center">
												<div class="col">
													<div id="m_chart_profit_share" class="m-widget14__chart" style="height: 160px">
														<div class="m-widget14__stat">
															45
														</div>
													</div>
												</div>
												<div class="col">
													<div class="m-widget14__legends">
														<div class="m-widget14__legend">
															<span class="m-widget14__legend-bullet m--bg-accent"></span>
															<span class="m-widget14__legend-text">
																37% Job Maintenance
															</span>
														</div>
														<div class="m-widget14__legend">
															<span class="m-widget14__legend-bullet m--bg-warning"></span>
															<span class="m-widget14__legend-text">
																47% Items Purchasing
															</span>
														</div>
														<div class="m-widget14__legend">
															<span class="m-widget14__legend-bullet m--bg-brand"></span>
															<span class="m-widget14__legend-text">
																19% Others
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--end:: Widgets/Profit Share-->
									</div>
								</div>
							</div>
						</div>
 	</div>
 </div>
 <div class="row">
 		<div class="col-md-8">
 			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<span class="m-portlet__head-icon m--hide">
								<i class="la la-gear"></i>
							</span>
							<h3 class="m-portlet__head-text">
								Stock By Products
							</h3>
						</div>
					</div>
				</div>
				<div class="m-portlet__body">
					<div id="MStockChart" style="height: 500px;"></div>
				</div>
			</div>
 		</div>
 		<div class="col-md-4">
 			<div class="col-md-12">
 				<div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 300px">
					<div class="m-portlet__head">
						<div class="m-portlet__head-caption">
							<div class="m-portlet__head-title">
								<h3 class="m-portlet__head-text">
									Yearly Stock Chart
								</h3>
							</div>
						</div>
						<div class="m-portlet__head-tools">
							<ul class="m-portlet__nav">
								<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
									<a href="#" class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
										Today
									</a>
									<div class="m-dropdown__wrapper">
										<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 36.5px;"></span>
										<div class="m-dropdown__inner">
											<div class="m-dropdown__body">
												<div class="m-dropdown__content">
													<ul class="m-nav">
														<!-- <li class="m-nav__item">
															<a href="" class="m-nav__link">
																<i class="m-nav__link-icon flaticon-share"></i>
																<span class="m-nav__link-text">
																	Activity
																</span>
															</a>
														</li> -->
														 
													</ul>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="m-portlet__body">
						<!--begin::Widget5-->
						<div class="m-widget20">
							<div id="TOTAL_STOCK_NUMBER" class="m-widget20__number m--font-success"></div>
							<div class="m-widget20__chart" style="height:160px;">
								<canvas id="StockSupplierChart"></canvas>
							</div>
						</div>
						<!--end::Widget 5-->
					</div>
				</div>
 			</div>
 			<div class="col-md-12">
 				<div class="m--space-30"></div>
 				<div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 300px">
						<div class="m-portlet__head">
							<div class="m-portlet__head-caption">
								<div class="m-portlet__head-title">
									<h3 class="m-portlet__head-text">
										Orders Chart
									</h3>
								</div>
							</div>
							<div class="m-portlet__head-tools">
								<ul class="m-portlet__nav">
									<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
										<a href="#" class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
											Today
										</a>
										<div class="m-dropdown__wrapper">
											<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 36.5px;"></span>
											<div class="m-dropdown__inner">
												<div class="m-dropdown__body">
													<div class="m-dropdown__content">
														<ul class="m-nav">
															<!-- <li class="m-nav__item">
																<a href="" class="m-nav__link">
																	<i class="m-nav__link-icon flaticon-share"></i>
																	<span class="m-nav__link-text">
																		Activity
																	</span>
																</a>
															</li> -->
														</ul>
													</div>
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<div class="m-portlet__body">
							<!--begin::Widget5-->
							<div class="m-widget20">
								<div class="m-widget20__number m--font-warning" id="TOTAL_ORDER"> 
								</div>
								<div class="m-widget20__chart" style="height:160px;">
									<canvas id="m_chart_bandwidth2"></canvas>
								</div>
							</div>
							<!--end::Widget 5-->
						</div>
					</div>
 			</div>
 		</div> 
 </div>
@endsection