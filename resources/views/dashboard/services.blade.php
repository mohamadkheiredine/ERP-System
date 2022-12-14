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
    <script src="{{ url('default/assets/app/js/service-dashboard.js') }}" type="text/javascript"></script> 
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
													Daily Sales ( USD )
												</h3>
												<span class="m-widget14__desc">
													Sales Volum showed by Day in the Current Week 
												</span>
											</div>
											<div class="m-widget14__chart" style="height:120px;">
												<canvas  id="m_chart_daily_sales_usd"></canvas>
											</div>
										</div>
										<!--end:: Widgets/Daily Sales-->
										<div class="m-widget14">
											<div class="m-widget14__header m--margin-bottom-30">
												<h3 class="m-widget14__title">
													Daily Sales (LBP )
												</h3>
												<span class="m-widget14__desc">
													Sales Volum showed by Day in the Current Week 
												</span>
											</div>
											<div class="m-widget14__chart" style="height:120px;">
												<canvas  id="m_chart_daily_sales_lbp"></canvas>
											</div>
										</div>
									</div>
									<div class="col-xl-4"> 
									</div>
								</div>
							</div>
						</div>
 	</div>
 	<div class="col-md-12">
		<div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 600px">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Transaction Details
						</h3>
					</div>
				</div>
				<div class="m-portlet__head-tools">
					 
				</div>
			</div>
			<div class="m-portlet__body">
				<div class="row">
					<div class="col-md-12 TransactionDetails"  style="max-height: 500px;overflow-y:scroll ">
					
					</div> 
				</div>
			</div>
		</div>
 	</div>
 	<div class="col-md-4">
 		<div class="row">
 			<div class="col-md-12">
 				<div class="m--space-30"></div>
 				<div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 500px">
						<div class="m-portlet__head">
							<div class="m-portlet__head-caption">
								<div class="m-portlet__head-title">
									<h3 class="m-portlet__head-text">
									 Yearly Invoices Chart (USD)
									</h3>
								</div>
							</div>
							<div class="m-portlet__head-tools">
								 
							</div>
						</div>
						<div class="m-portlet__body">
							<!--begin::Widget5-->
							<div class="m-widget20">
								<div class="m-widget20__number m--font-warning" id="TOTAL_ORDER_USD"> 
								</div>
								<div class="m-widget20__chart" style="height:250px;">
									<canvas id="m_chart_invoices_USD"></canvas>
								</div>
							</div>
							<!--end::Widget 5-->
						</div>
					</div>
 			</div>
 		</div> 	 
 	</div>
 	<div class="col-md-4">
 		<div class="row">
 			<div class="col-md-12">
 				<div class="m--space-30"></div>
 				<div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 500px">
						<div class="m-portlet__head">
							<div class="m-portlet__head-caption">
								<div class="m-portlet__head-title">
									<h3 class="m-portlet__head-text">
									 Yearly Invoices Chart (LBP)
									</h3>
								</div>
							</div>
							<div class="m-portlet__head-tools">
								 
							</div>
						</div>
						<div class="m-portlet__body">
							<!--begin::Widget5-->
							<div class="m-widget20">
								<div class="m-widget20__number m--font-warning" id="TOTAL_ORDER_LBP"> 
								</div>
								<div class="m-widget20__chart" style="height:250px;">
									<canvas id="m_chart_invoices_LBP"></canvas>
								</div>
							</div>
							<!--end::Widget 5-->
						</div>
					</div>
 			</div>
 		</div> 
 	</div>
 	<div class="col-md-4">
 		<div class="row">
 			<div class="col-md-12">
 				<div class="m--space-30"></div>
 				<div class="m-portlet m-portlet--bordered-semi m-portlet--half-height m-portlet--fit " style="min-height: 500px">
						<div class="m-portlet__head">
							<div class="m-portlet__head-caption">
								<div class="m-portlet__head-title">
									<h3 class="m-portlet__head-text">
									 Yearly Invoices Chart (EUR)
									</h3>
								</div>
							</div>
							<div class="m-portlet__head-tools">
								 
							</div>
						</div>
						<div class="m-portlet__body">
							<!--begin::Widget5-->
							<div class="m-widget20">
								<div class="m-widget20__number m--font-warning" id="TOTAL_ORDER_EUR"> 
								</div>
								<div class="m-widget20__chart" style="height:250px;">
									<canvas id="m_chart_invoices_EUR"></canvas>
								</div>
							</div>
							<!--end::Widget 5-->
						</div>
					</div>
 			</div>
 		</div> 
 	</div>
 	<div class="col-md-4">
 		<div class="card card-custom gutter-b"> 
			<div class="card-body">
				<div id="services_charts" style="height: 500px;"></div>
			</div>
		</div>
 	</div>
 	</div>
 	
 </div>
 
@endsection