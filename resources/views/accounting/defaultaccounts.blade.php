<?php
/***********************************************************
defaultaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 21, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Page to manage default accounts 
***********************************************************/

?>



@extends('layouts.layout',['page_title' => "Default Accounts"])

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
<script type="text/javascript" src="{{ url('js/modules/defaultaccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/defaultaccounts.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Default Accounts</h3>
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
    	<!--begin: Search Form -->
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
					<div class="col-md-4">
                       <br/>
					</div>
					<div class="col-md-4">
                       <br/>
					</div>
				</div>
			</div>
			<div class="col-xl-12 order-1 order-xl-12 align-right" style="height:20px;">
				
			</div>
		</div>
	</div>
	<!--end: Search Form -->
      <!--begin: Datatable -->
      <form name="frm_save_accounts" id="FRM_SAVE_ACCOUNTS">
                  {!! csrf_field() !!}
    		<div id="LstDefaultAccounts" class="table-responsive">
		 
			</div>  
      </form>
	<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
		<div class="m-separator m-separator--dashed d-xl-none"></div>
	</div>
	<!--end: Datatable -->
	<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
			<button type="button" name="btn_save_info" class="btn btn-info" >Save Info</button>
		</div>
    </div>
 </div>


@endsection