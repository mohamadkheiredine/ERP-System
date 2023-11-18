<?php
/***********************************************************
deals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Acount Deals Management
***********************************************************/



?>

@extends('layouts.layout',['page_title' => "Deals Management"])

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
<script type="text/javascript" src="{{ url('js/modules/deals.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/deals.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Deals Management</h3>
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
    <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
								<span class="m-input-icon__icon m-input-icon__icon--left">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
                            <select class="bs-select form-control" id="AD_ACCOUNT" name="ad_account">
                    			<option value="0">-- Select Account --</option>
                                @foreach($lst_accounts as $index => $acc_info)
                                  <option value="{{ $acc_info->ca_id }}">{{ $acc_info->ca_account_name }}</option>
                                @endforeach
                            </select>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ url('crm/accounts/deals/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Deal
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="table-responsive">
<table class="table" id="html_table" width="100%">
		<thead>
			<tr>
				<th title="Id"> ID </th>
				<th title="Deal ref"> Deal ref </th>
				<th title="Deal Title"> Deal Title </th>
				<th title="Account Name"> Account Name </th>
				<th title="Account Name"> Deal Amount </th>
				<th style="width:4px !important;" nowrap title="#">edit</th>
				<th style="width:4px !important;" nowrap title="#">Delete</th>
			</tr>
		</thead>
		<tbody id="LstAccountDeals">
	
			</tbody>
</table>
		</div>
		<!--end: Datatable -->
    </div>
</div>

 
@endsection