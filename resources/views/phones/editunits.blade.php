<?php
/***********************************************************
editunits.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/



?>
@extends('layouts.layout',['page_title' => "Phone Line Management"])

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
<script type="text/javascript" src="{{ url('js/modules/units.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/phones/saveunits.js') }}"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Edit Existing Package
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
             <form name="frm_save_units" id="FORM_SAVE_UNITS">
                <div class="form-body">
                     <span id="hidden_fields">
                       {!! csrf_field() !!}
                       <input type="hidden" name="pu_id" id="PU_ID" value="{{ $phone_units->pu_id }}" />
                    </span>
                    <div class="alert alert-success" style="display:none">
            				<strong>Success!</strong> Phone Units Information is saved successfully!
            			</div>
            			<div class="alert alert-danger" style="display:none">
            				<strong>Error!</strong> You have some form errors. Please check below.
            			</div>
                    <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Units Label <span class="required"> * </span></label>
                                    <input type="text" name="pu_unit_label" id="PU_UNIT_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $phone_units->pu_unit_label }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                              <div class="form-group">
                                    <label class="control-label"> Units <span class="required"> * </span></label>
                                    <input type="number" min="0" max="20"  step="1" name="pu_units" id="PU_UNITS" class="form-control" required="required" maxlength="15"  value="{{ $phone_units->pu_units }}" />
                                </div>
                        </div>
                        <div class="col-md-4">
                          	<div class="form-group">
                                <label class="control-label"> Units Ammount <span class="required"> * </span></label>
                                <input type="text" name="pu_unit_amount" id="PL_UNIT_AMOUNT" class="form-control" required="required" maxlength="15"  value="{{ $phone_units->pu_unit_amount }}" />
                            </div>
                        </div> 
                         <div class="col-md-4">
                            <div class="form-group">
                                <label> Stock Currency </label>
                                <select class="bs-select form-control" name="pu_currency_id" id="PU_CURRENCY_ID" data-actions-box="true">
                                        @foreach( $lst_currencies as $key => $curr_info )
                                                <option {{ $phone_units->pu_currency_id == $curr_info->cc_id ? "selected" : "" }}  value="{{ $curr_info->cc_id }}">{{ $curr_info->cc_currency_code . " - " . $curr_info->cc_currency_name }}</option>
                                         @endforeach 
                                </select>
                            </div>
                        </div>
                    </div>
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_package" id="BTN_SAVE_PACKAGE"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
	</div>
</div>

@endsection