<?php
/***********************************************************
vataccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 25, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Vat Accounts Manager 
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "VAT Accounts"])

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
<script type="text/javascript" src="{{ url('js/modules/vataccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/vataccounts.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title"> VAT Accounts</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              <li class="m-nav__item">
						<a data-action_type="PRINT"  href="#" class="m-nav__link quickactions">
							<i class="m-nav__link-icon fa fa-print"></i>
							<span class="m-nav__link-text">
								Print
							</span>
						</a>
					</li>
					<li class="m-nav__item">
						<a data-action_type="EXPORT_AS_CSV"  href="#" class="m-nav__link quickactions">
							<i class="m-nav__link-icon fa fa-download"></i>
							<span class="m-nav__link-text">
								Export As CSV
							</span>
						</a>
					</li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <!--begin: Search Form -->
    	<div class="col-md-12">
    		<div class="row align-items-center">
    			<div class="col-xl-8 order-2 order-xl-1">
    				<div class="row align-items-center">
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
    					<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
    					</div>
    					<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
    					</div>
    				</div>
    			</div>
    			<div class="col-xl-12 order-1 order-xl-12 m--align-right">
    				<div class="m-separator m-separator--dashed d-xl-none"></div>
    			</div>
    		</div>
    	</div>
    	<!--end: Search Form -->
          <!--begin: Datatable -->
    	<div id="LstVatAccounts" class="table-response">
    		 
    	</div>
    	<!--end: Datatable -->
    	<div class="col-xl-12 order-1 order-xl-12 align-right" style="margin-top: 12px;">
    			<div class="m-separator m-separator--dashed d-xl-none"></div>
    		</div>
    		<div class="col-xl-12 order-1 order-xl-12 align-right">
				<a href="{{ url('accounting/vataccounts/addform') }}" class="btn btn-info">
					<span>
						<i class="flaticon-grid-menu-v2"></i>
						<span>
							Add Account
						</span>
					</span>
				</a>
			</div>
    </div>
 </div>
@endsection