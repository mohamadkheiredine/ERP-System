<?php
/***********************************************************
maindashboard.blade.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Nov 3, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Dashboard"])

@section('plugins')
	 <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
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
<!--begin::Row-->
	<div class="row g-5 g-xl-8">
		<!--begin::Col-->
		<div class="col-xl-4">
			<!--begin::Misc Widget 1-->
			<div class="row mb-5 mb-xl-8 g-5 g-xl-8">
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="#" style="text-align: center" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="fa-solid fa-users fa-xl" style="font-size: 48px;margin-bottom: 3px;"></i>
							<span class="fs-4 fw-bold">Customers</span><br/>
							<span class="fs-4 fw-bold" id="CustomersCount"></span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="#" style="text-align: center" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="fa-solid fa-receipt fa-xl" style="font-size: 48px;margin-bottom: 3px;"></i>
							<span class="fs-4 fw-bold">Orders</span><br/>
							<span class="fs-4 fw-bold" id="OrdersCount"></span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="#" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="fa-solid fa-truck-field fa-xl" style="font-size: 48px;margin-bottom: 3px;"></i>
							<span class="fs-4 fw-bold">Suppliers</span><br/>
							<span class="fs-4 fw-bold" id="SuppliersCount"></span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6" style="display:none">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="#" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-abstract-26 fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<span class="fs-4 fw-bold">Hot Picks</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6" style="display:none">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/apps/projects/project.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-basket fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
								<span class="path4"></span>
							</i>
							<span class="fs-4 fw-bold">Latest Trands</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-6" style="display:none">
					<!--begin::Card-->
					<div class="card card-stretch">
						<!--begin::Link-->
						<a href="../../demo9/dist/apps/projects/users.html" class="btn btn-flex btn-text-gray-800 btn-icon-gray-400 btn-active-color-primary bg-body flex-column justfiy-content-start align-items-start text-start w-100 p-10">
							<i class="ki-duotone ki-rocket fs-2tx mb-5 ms-n1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							<span class="fs-4 fw-bold">New Arrivals</span>
						</a>
						<!--end::Link-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Col-->
			</div>
		</div>
		<!--end::Col-->
		<!--begin::Col-->
		<div class="col-xl-8 ps-xl-12">
		 	<div class="row">
		 		<div class="col-md-6">

		 		</div>
		 		<div class="col-md-6">
		 		</div>
		 	</div>
		</div>
		<div class="container mt-5">
        <div class="row">
            <!-- Sales Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                           <h2>Stock Amount By Category</h2>
                    </div>
                    <div class="card-body">
                           <div id="StockAmountCategory" class="DashboardItem">


                          </div>
                    </div>
                </div>
            </div>
            <!-- Inventory Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                         <h2>Inventory Levels</h2>
                    </div>
                    <div class="card-body">
                        <div id="InventoryLevels" class="DashboardItem">


                          </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <!-- Expenses Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>List of Maintenance For this Month</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Client Code</th>
                                    <th>Client Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Region</th>
                                    <th>Area</th>
                                    <th>Phone</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lst_inboundcalls as $index => $call_info)
                                <tr>
                                    <td>{{ $call_info->Client ? $call_info->Client->ca_account_code : "-" }}&nbsp;</td>
                                    <td>{{ $call_info->Client ? $call_info->Client->ca_account_name : "-" }}</td>
                                    <td>{{ $call_info->ic_call_date }}</td>
                                    <td>{{ $call_info->ic_call_start_time }}</td>
                                    <td>{{ $call_info->Client  ? $call_info->Client->ca_billing_region : "-" }}</td>
                                    <td>{{ $call_info->Client  ? $call_info->Client->ca_billing_area : "-" }}</td>
                                    <td>{{ $call_info->Client  ? $call_info->Client->ca_account_mobile : "-" }}</td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Revenue Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Pending Bills</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Client Code</th>
                                    <th>Client Name</th>
                                    <th>Bill Nbr</th>
                                    <th>Date</th>
                                    <th>Phone</th>
                                    <th>Payment Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lst_bills as $index => $bill_info)
                                    <tr>
                                        <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_code : "-" }}&nbsp;</td>
                                        <td>{{ $bill_info->Client ? $bill_info->Client->ca_account_name : "-" }}</td>
                                        <td>{{ $bill_info->ip_billing_nbr }}</td>
                                        <td>{{ $bill_info->ip_billing_date }}</td>
                                        <td>{{ $bill_info->Client->ca_account_mobile }}</td>
                                        <td>{{ $bill_info->ip_payment_amount }} {{ $bill_info->Currency ? $bill_info->Currency->cc_currency_code : "" }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	</div>
	<!--end::Row-->
@endsection
