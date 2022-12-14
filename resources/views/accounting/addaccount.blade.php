<?php
/***********************************************************
addaccount.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

$company_country = session("company_country");
?>
@extends('layouts.layout',['page_title' => "Chart of Accounts"])

@section('themes')
<style>
th{
    cursor: pointer;
}
#ModelPopUp{
	width:800px;
}
</style>
<link rel="stylesheet" href="{{ url('default/assets/plugins/jstree/dist/themes/default/style.min.css') }}" />
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('default/assets/plugins/jstree/dist/jtree.min.js') }}"></script>
<script type="text/javascript" src="{{ url('js/modules/chartaccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/saveaccounts.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">Add New Account</h3>
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
			<div class="row">
				<div class="col-md-9">
					<form name="frm_save_account" id="FORM_SAVE_ACCOUNT">
                        <div class="form-body">
                             <span id="hidden_fields">
                              <div class="form-group">
                                {!! csrf_field() !!}
                                <input type="hidden" name="is_save_new" value="0" />
                                 </div>
                            </span>
                            <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> Account Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
                			<div class="row">
                				<div class="col-md-12">
                					<div class="row">
                                        <div class="col-md-4">
                                              <div class="form-group">
                                                    <label class="control-label"> Account Ref </label>
                                                    <input type="text" name="aa_account_ref" id="AA_CATEGORY_REF" class="form-control"  maxlength="15"  value="" />
                                                </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label">Account <span class="required"> * </span></label>
                                                <input type="text" name="aa_account" id="AA_ACCOUNT" class="form-control" required="required" maxlength="10"  value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Parent Account </label>
                                                <select class="bs-select form-control" name="aa_sub_account" id="AA_SUB_ACCOUNT" data-actions-box="true">
                                                        <option value="">No Parent</option>
                                                        @foreach ( $lst_accounts as $key => $acc_info )
                                                                <option data-account="{{ $acc_info->aa_account }}" value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account . " - " . $acc_info->aa_account_label  }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label">Account Label <span class="required"> * </span></label>
                                                <input type="text" name="aa_account_label" id="AA_ACCOUNT_LABEL" class="form-control" maxlength="255"  value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label">Account Information <span class="required"> * </span></label>
                                                <input type="text" name="aa_account_information" id="AA_ACCOUNT_INFORMATION" class="form-control" maxlength="255"  value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Category </label>
                                                <select class="bs-select form-control" name="aa_category_id" id="AA_CATEGORY_ID" data-actions-box="true">
                                                        <option value=""> No Category </option>
                                                        @foreach ( $lst_account_categories as $key => $category_info )
                                                                <option  value="{{ $category_info->ac_id }}">{{ $category_info->ac_id ." - " . $category_info->ac_category_title }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> Country </label>
                                                <select class="bs-select form-control" name="fk_country_id" id="FK_COUNTRY_ID" data-actions-box="true">
                                                        <option value="">Country</option>
                                                        @foreach ( $lst_countries as $key => $country_info )
                                                                <option {{ $company_country == $country_info->id ? "selected" : "" }} value="{{ $country_info->id }}">{{ $country_info->name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label">Group Account</label>
                                                <input type="text" name="aa_group_account" id="AA_GROUP_ACCOUNT" class="form-control" maxlength="255"  value="" />
                                            </div>
                                        </div>
                                         <div class="col-md-4">
                                             <div class="form-group">
                                                <label class="control-label">Subgroup Account</label>
                                                <input type="text" name="aa_subgroup_account" id="AA_SUBGROUP_ACCOUNT" class="form-control" maxlength="255"  value="" />
                                            </div>
                                        </div>
                                    </div>
                				</div>
                			</div>
                            
                           <div class="row" style="height:5px;"></div>
                            <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3" align="right">
                                     <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                     <button type="submit" name="btn_save_account" id="BTN_SAVE_ACCOUNT"  class="btn btn-info">Save</button>
                                     <button type="submit" name="btn_save_new_account" id="BTN_SAVE_NEW_ACCOUNT"  class="btn btn-success">Save & New </button>
                                </div>
                            </div>
                        </div>
                    </form>
				</div>
				<div class="col-md-3" id="AccountsTree" style="overflow-x: scroll;">
				<ul>
				@foreach($lst_accounts as $index => $account_info)
					@if(strlen($account_info->aa_account) <= 2 )
						<li data-id="{{ $account_info->aa_id }}" data-account_id="{{ $account_info->aa_account }}">{{ $account_info->aa_account }}&nbsp;-&nbsp;{{ $account_info->aa_account_label }}</li>
					@endif
				@endforeach
				</ul>
				</div>
			</div>
	</div>
</div>

@endsection