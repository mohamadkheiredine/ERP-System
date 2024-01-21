<?php
/***********************************************************
accountstatment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 28, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
Report Page for Account Statment
***********************************************************/

?>
@extends('layouts.layout',['page_title' => "Accounting Reports"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
.grouprow{
	cursor: pointer;
}
.Transaction{
	cursor: pointer;
}
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/transactions.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/statmentdetails.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Account Statment Details</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" data-action_type="PRINT" href="#">Print</a></li>
                    <li><a class="dropdown-item" data-action_type="EXPORT_AS_CSV" href="#">Export As CSV</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    <div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-12 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
							<div class="form-group">
                                <label> Search Key </label>
                                <input type="text" name="search_query" id="SEARCH_QUERY" value="" class="form-control" />
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label> From Date </label><br/>
                                <input type="text" name="start_date" id="START_DATE" value="" class="form-control" />
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label> To Date </label><br/>
                                <input type="text" name="end_date" id="END_DATE" value="" class="form-control" />
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group"><br/>
                                <label> <input type="checkbox" name="ck_include_before" id="CK_INCLUDE_BEFORE" value="1" />Including Before </label>
                            </div>
						</div>
						<div class="col-md-4">
							 <div class="form-group">
                                <label> Order By </label><br/>
                                 <select name="order_by" id="ORDER_BY" class="form-control">
                                 	<option value="">Default Order</option>
                                 	<option value="transaction_date">Transaction Date</option>
                                 	<option value="creation_date">Creation Date</option>
                                 </select>
                            </div>
						</div>
					</div>
				</div>
				<div class="col-md-12" style="height:15px">
					
				</div>
			</div>
		</div>
		<input type="hidden" name="detail_account_id" value="" />
		<input type="hidden" name="sel_currency_id" value="" />
		<input type="hidden" name="fisical_year" value="{{ date('Y') }}" />
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="col-md-12 table-responsive" id="LstAccountStatment">

		</div>
		<!--end: Datatable -->
    </div>
</div>
@endsection