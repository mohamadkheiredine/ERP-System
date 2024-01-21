<?php
/***********************************************************
bidding.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Supplier Management"])

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
<script type="text/javascript" src="{{ url('js/modules/bidding.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/srm/bidding.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Supplier Bidding</h3>
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
    <div class="col-md-12">
									<div class="row align-items-center">
										<div class="col-xl-8 order-2 order-xl-1">
											<div class="form-group m-form__group row align-items-center">
												<div class="col-md-4">
													<div class="d-flex align-items-center">
                        									<!--begin::Input group-->
                        									<div class="position-relative w-md-400px me-md-2">
                        										<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
                        											<span class="path1"></span>
                        											<span class="path2"></span>
                        										</i>
                        										<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
                        									</div>
                        									<!--end::Input group-->
                        								</div>

												</div>
												<div class="col-md-4"><br/>
												</div>
												<div class="col-md-4"><br/>
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 m--align-right">
											<br/>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
		                          	<div class="row">
									<div class="col-md-10"></div>
									<div class="col-md-2">
										<a href="{{ url('srm/bidding/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Bidding
													</span>
												</span>
											</a>
									</div>
								</div>
								<div class="table-responsive col-md-12">
                                        <table class="table" id="html_table" width="100%">
                                        		<thead>
                                        			<tr>
                                        				<th title="#"><input type="checkbox" name="ck_all_sb" id="CK_ALL_SB" class="checkboxes" value="1" /></th>
                                        				<th title="Id"> ID </th>
                                        				<th title="Bidding Ref"> Bidding Ref </th>
                                        				<th title="Bidding Title"> Bidding Title </th>
                                        				<th title="Start Date"> Start Date </th>
                                        				<th title="End Date"> End Date </th>
                                        				<th style="width:2px;" nowrap title="#"> edit </th>
                                        				<th style="width:2px;" nowrap title="#"> Delete </th>
                                        			</tr>
                                        		</thead>
                                        		<tbody  id="LstBidding">
                                        
                                        		</tbody>
                                        </table>

								</div>
								<!--end: Datatable -->
								<div class="row">
									<div class="col-md-10"></div>
									<div class="col-md-2">
										<a href="{{ url('srm/bidding/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Bidding
													</span>
												</span>
											</a>
									</div>
								</div>
    </div>
</div>
 
@endsection