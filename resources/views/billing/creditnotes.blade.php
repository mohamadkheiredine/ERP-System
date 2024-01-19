<?php
/***********************************************************
creditnotes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 31, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Billing Management"])

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
<script type="text/javascript" src="{{ url('js/modules/creditnotes.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/billing/cnmanagement.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Credit Notes</h3>
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
				<li class="m-nav__item">
					<a data-action_type="IMPORT"  href="#" class="m-nav__link quickactions">
						<i class="m-nav__link-icon fa fa-upload"></i>
						<span class="m-nav__link-text">
							Import
						</span>
					</a>
				</li>
				<li class="m-nav__item">
					<a  data-action_type="DOWNLOAD_TEMPLATE"  href="#" class="m-nav__link quickactions">
						<i class="m-nav__link-icon flaticon-download"></i>
						<span class="m-nav__link-text">
							Download Import Template
						</span>
					</a>
				</li> 
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <input type="hidden" name="page_number" value="1" />
		<!--begin: Search Form -->
		<div class="row">
		<div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch" name="general_search" />
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="CN_ACCOUNT_PAYABLE" name="cn_account_payable">
                            			<option value="0">-- Select Account --</option>
                                        @foreach($lst_chart_accounts as $index => $account_info)
                                          <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref }} - {{ $account_info->aa_account_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						 <div class="col-md-4">
							 <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="CN_ACCOUNT_RECEIVABLE" name="cn_account_receivable">
                            				<option value="0">-- Select Account --</option>
                                        @foreach($lst_chart_accounts as $index => $account_info)
                                          <option value="{{ $account_info->aa_id }}">{{ $account_info->aa_account_ref }} - {{ $account_info->aa_account_label }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						<div class="col-md-12">&nbsp;</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text" placeholder=" From Date" name="cn_start_date" id="CN_START_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <input type="text"  placeholder="To Date" name="cn_end_date" id="CN_END_DATE" value="" class="form-control" />
                            </div>
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</div>
		</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
          <div class="row">
		<div class="col-md-12 table-responsive">
            <table class="table">
            		<thead>
            			<tr>
            				<th style="width:2%" title="#">#</th>
            				<th style="width:2%" title="Id"> ID </th>
            				<th  style="width:15%" title="Account Payable"> Account Payable </th>
            				<th  style="width:15%" title="Account Receivable"> Account Receivable </th>
            				<th  style="width:20%" title="Label"> Label </th> 
            				<th  style="width:12%" title="Amount"> Amount </th> 
            				<th style="width:2px;" nowrap title="edit"> edit </th>
            				<th style="width:2px;" nowrap title="delete"> Delete </th>
            			</tr>
            		</thead>
            		<tbody id="LstCreditNotes">
            		</tbody>
            </table>
		</div>
		</div>
    	 <div class="row">
             <div class="col-md-10" align="left">
                <ul id="CreditNotesPagination" class="pagination-sm"></ul>
             </div>
             <div class="col-md-2" align="right"></div>
         </div>
		<div class="row">
			<div class="col-md-12 order-1 order-xl-2 m--align-right">
					<a href="{{ url('billing/creditnotes/addform') }}" class="btn btn-info">
						<span>
							<i class="flaticon-tabs"></i>
							<span>
								New <b>Credit Note</b>
							</span>
						</span>
					</a> 
				</div>
		</div>
    </div>
</div>

@endsection