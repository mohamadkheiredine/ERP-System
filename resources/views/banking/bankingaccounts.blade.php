<?php
/***********************************************************
bankingaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Banking Accounts Management"])

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
<script type="text/javascript" src="{{ url('js/modules/banking.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/banking/bankingmanagement.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Banking Accounts Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              		<li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
                    <li><a class="dropdown-item" data-action_type="IMPORT" href="#">Import</a></li>
                    <li><a class="dropdown-item" data-action_type="DOWNLOAD_TEMPLATE" href="#">Download Import Template</a></li>
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
									<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" tabindex="1" />
								</div>
								<!--end::Input group-->
							</div>
    					</div>
    					<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
    					</div>
    					<div class="col-md-4">
                           <div class="d-md-none m--margin-bottom-10"></div>
    					</div>
    				</div>
    			</div>
    			<div class="col-xl-4 order-1 order-xl-2 align-right">
    				<a href="{{ url('banking/accounts/addform') }}" class="btn btn-info">
    					<span>
    						<i class="fas fa-user"></i>
    						<span>
    							New Account
    						</span>
    					</span>
    				</a>
    				<br/>
    			</div>
    		</div>
    	</div>
    	<!--end: Search Form -->
          <!--begin: Datatable -->
    	<div class="row" >
    			<div class="col-md-12 table-responsive">
            			<table class="table table-row-dashed table-row-gray-300 gy-7"  width="100%">
                    		<thead>
                    			<tr class="fw-bold fs-6 text-gray-800">
                    				<th title="#">#</th>
                    				<th title="Id"> ID </th>
                    				<th title="Account Number"> Account Number </th>
                    				<th title="Bank Name"> Bank Name </th>
                    				<th title="Journal"> Journal </th>
                    				<th title="Chart Account"> Chart Account </th>
                    				<th style="width:2px;" nowrap title="#"> edit </th>
                    				<th style="width:2px;" nowrap title="#"> Delete </th>
                    			</tr>
                    		</thead>
                    		<tbody id="LstBankAccounts">
                    	
                    		</tbody>
                    </table>
    			</div> 
    	</div>
    	<div class="col-xl-12 order-1 order-xl-2 align-right">
    				<a href="{{ url('banking/accounts/addform') }}" class="btn btn-info">
    					<span>
    						<i class="fas fa-user"></i>
    						<span>
    							New Account
    						</span>
    					</span>
    				</a>
    				<br/>
    			</div>
    	</div>
    </div>
</div>
 
@endsection