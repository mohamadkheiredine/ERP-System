<?php
/***********************************************************
paymenttypes.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 16, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Payment Types Management"])

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
<script type="text/javascript" src="{{ url('js/libraries/billing/paymenttypes.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
<div class="m-portlet__head">
	<div class="m-portlet__head-caption">
		<div class="m-portlet__head-title">
			<h3 class="m-portlet__head-text">
				Payment Types Management
			</h3>
		</div>
	</div>
	<div class="m-portlet__head-tools">
		<ul class="m-portlet__nav">
			<li class="m-portlet__nav-item">
				<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
					<a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
						<i class="la la-ellipsis-h m--font-brand"></i>
					</a>
					<div class="m-dropdown__wrapper">
						<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
						<div class="m-dropdown__inner">
							<div class="m-dropdown__body">
								<div class="m-dropdown__content">
									<ul class="m-nav">
										<li class="m-nav__section m-nav__section--first">
											<span class="m-nav__section-text">
												Quick Actions
											</span>
										</li>
										<li class="m-nav__item">
											<a href="" class="m-nav__link">
												<i class="m-nav__link-icon flaticon-share"></i>
												<span class="m-nav__link-text">
													Print
												</span>
											</a>
										</li>
										<li class="m-nav__item">
											<a href="" class="m-nav__link">
												<i class="m-nav__link-icon flaticon-chat-1"></i>
												<span class="m-nav__link-text">
													Export As CSV
												</span>
											</a>
										</li>
										<li class="m-nav__item">
											<a href="" class="m-nav__link">
												<i class="m-nav__link-icon flaticon-multimedia-2"></i>
												<span class="m-nav__link-text">
													Import
												</span>
											</a>
										</li>
										<li class="m-nav__item">
											<a href="" class="m-nav__link">
												<i class="m-nav__link-icon flaticon-multimedia-2"></i>
												<span class="m-nav__link-text">
													Download Import Template
												</span>
											</a>
										</li>

									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
<div class="m-portlet__body">
      <!--begin: Datatable -->
	<div class="row" id="LstBankAccounts">
		<div class="col-md-12">
		<form name="frm_save_payment_types" id="FRM_SAVE_PAYMENT_TYPES">
    	 <div class="row">
    	 	<div class="col-md-12">
    	 		<span id="hidden_fields">
            		 {!! csrf_field() !!}
            		</span>
            		<table class="table m-table m-table--head-separator-primary">
            			<thead>
            				<tr>
            					<th style="width:10%">ID</th>
            					<th style="width:60%">Label</th>
            					<th style="width:30%">Account</th>
            				</tr>
            			</thead>
            			<tbody>
            				 @foreach ($lst_payment_types as $key => $pt_info)
            				 	<tr>
                					<th scope="row">{{ $pt_info->pt_id }}<input type="hidden" name="pt_id[]" value="{{ $pt_info->pt_id }}" /></th>
                					<td><input  class="form-control" maxlength="255" type="text" name="pt_payment_type[]" value="{{ $pt_info->pt_payment_type }}" /></td>
                					<td>
                						 <select class="form-control" name="pt_payment_account[]">
                                                    <option value="">Payment Type Account</option>
                                                    @foreach ( $lst_chart_accounts as $key => $acc_info )
                                                            <option {{ $pt_info->pt_payment_account == $acc_info->aa_id ? "selected" : ""  }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                                    @endforeach
                                            </select>
                					</td>
                				</tr>
            				 @endforeach
            		</tbody>
            	 </table>
    	 	</div>
    	 </div>
    	  <div class="row">
    	 	<div class="col-md-12" align="right" >
    	 		<button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
    	 		<button type="button" name="btn_save_pay_type" id="BTN_SAVE_PAY_TYPE" class="btn btn-success">Save info</button>
    	 	</div>
    	 </div>
    	 </div>
	 </form>
	</div>
	<!--end: Datatable -->
	</div>
</div>
@endsection