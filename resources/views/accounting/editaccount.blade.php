<?php
/***********************************************************
editaccount.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 14, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


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
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/chartaccounts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/saveaccounts.js') }}"></script>
<script type="text/javascript" src="{{ url('theme/style/src/assets/plugins/custom/jstree/jstree.bundle.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Edit Account</h3>
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
    <form name="frm_save_account" id="FORM_SAVE_ACCOUNT">
                <div class="form-body">
                     <span id="hidden_fields">
                      <div class="form-group">
                        {!! csrf_field() !!}
                        <input type="hidden" name="aa_id" id="AA_ID" value="{{ $chartaccount_info->aa_id }}" />
                         </div>
                    </span>
                    <div class="alert alert-success" style="display:none">
        				<strong>Success!</strong> Account Information is saved successfully!
        			</div>
        			<div class="alert alert-danger" style="display:none">
        				<strong>Error!</strong> You have some form errors. Please check below.
        			</div>
        			<div class="row">
        				<div class="col-md-8">
        					<div class="row">
                                <div class="col-md-4">
                                      <div class="form-group">
                                            <label class="control-label"> Account Ref </label>
                                            <input type="text" name="aa_account_ref" id="AA_CATEGORY_REF" class="form-control"  maxlength="15"  value="{{ $chartaccount_info->aa_account_ref }}" />
                                        </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="form-group">
                                        <label class="control-label">Account <span class="required"> * </span></label>
                                        <input type="text" name="aa_account" id="AA_ACCOUNT" class="form-control" required="required" maxlength="10"  value="{{ $chartaccount_info->aa_account }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> SubAccount </label>
                                        <select class="bs-select form-control" name="aa_sub_account" id="AA_SUB_ACCOUNT" data-actions-box="true">
                                                <option value="">Parent Account</option>
                                                @foreach ( $lst_accounts as $key => $acc_info )
                                                        <option {{ $chartaccount_info->aa_sub_account == $acc_info->aa_id ? "selected" : "" }} value="{{ $acc_info->aa_id }}">{{ $acc_info->aa_account }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                     <div class="form-group">
                                        <label class="control-label">Account Label <span class="required"> * </span></label>
                                        <input type="text" name="aa_account_label" id="AA_ACCOUNT_LABEL" class="form-control" maxlength="255"  value="{{ $chartaccount_info->aa_account_label }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="form-group">
                                        <label class="control-label">Account Information <span class="required"> * </span></label>
                                        <input type="text" name="aa_account_information" id="AA_ACCOUNT_INFORMATION" class="form-control" maxlength="255"  value="{{ $chartaccount_info->aa_account_information }}" />
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label> Category </label>
                                        <select class="bs-select form-control" name="aa_category_id" id="AA_CATEGORY_ID" data-actions-box="true">
                                                <option value=""> No Category </option>
                                                @foreach ( $lst_account_categories as $key => $category_info )
                                                        <option {{ $chartaccount_info->aa_category_id == $category_info->ac_id ? "selected" : ""  }}  value="{{ $category_info->ac_id }}">{{ $category_info->ac_id ." - " . $category_info->ac_category_title }}</option>
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
                                                        <option {{ $chartaccount_info->fk_country_id == $country_info->id ? "selected" : "" }} value="{{ $country_info->id }}">{{ $country_info->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                        <label class="control-label">Group Account</label>
                                        <input type="text" name="aa_group_account" id="AA_GROUP_ACCOUNT" class="form-control" maxlength="15"  value="{{ $chartaccount_info->aa_group_account }}" />
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                        <label class="control-label">Subgroup Account</label>
                                        <input type="text" name="aa_subgroup_account" id="AA_SUBGROUP_ACCOUNT" class="form-control" maxlength="15"  value="{{ $chartaccount_info->aa_subgroup_account }}" />
                                    </div>
                                </div> 
                            </div>
        				</div>
        				<div class="col-md-4">
        				
        				</div>
        			</div>
                    
                   <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                             <button type="submit" name="btn_save_account" id="BTN_SAVE_ACCOUNT"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>


@endsection