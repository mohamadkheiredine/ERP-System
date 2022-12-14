<?php
/***********************************************************
addexchangerate.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


?>
@extends('layouts.layout',['page_title' => "Exchange Rate"])

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
<script type="text/javascript" src="{{ url('js/modules/exchangerates.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/saveexchangerates.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Add Exchange Rate
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
             <form name="frm_save_rate" id="FORM_SAVE_RATE">
                <div class="form-body">
                     <span id="hidden_fields">
                      {!! csrf_field() !!}
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Exchange Rate Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Source Currency <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="er_from_currency" id="ER_FROM_CURRENCY" data-actions-box="true">
                                            <option value="">-- Select Currency --</option>
                                            @foreach ( $lst_currencies as $key => $currency_info )
                                                    <option {{ session("company_currency") == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div> 
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Destination Currency <span class="required"> * </span></label>
                                     <select class="bs-select form-control" name="er_to_currency" id="ER_TO_CURRENCY" data-actions-box="true">
                                            <option value="">-- Select Currency --</option>
                                            @foreach ( $lst_currencies as $key => $currency_info )
                                                    <option value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code . " - " . $currency_info->cc_currency_name  }}</option>
                                            @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Currency Rate  <span class="required"> * </span></label>
                                    <input type="text" name="er_exchange_rate" id="ER_EXCHANGE_RATE" class="form-control" required="required" maxlength="255"  value="" />
                                </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_exchange_rates" id="BTN_EXCHANGE_RATE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection