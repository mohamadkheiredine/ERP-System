<?php
/***********************************************************
adddeals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

 
?>
@extends('layouts.layout',['page_title' => "Deals Management > edit Deal Information"])

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
<script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
<script type="text/javascript" src="{{ url('js/modules/deals.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/savedeals.js') }}"></script>
@endsection

@section('content')

<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">

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
                                     <form name="frm_save_deals" id="FORM_SAVE_DEALS">
                                        <div class="form-body">
                                             <span id="hidden_fields">
                                              {!! csrf_field() !!}
                                              <input type="hidden" name="ad_id" value="{{ $deal_info->ad_id }}" />
                                            </span>
                                            <div class="alert alert-success" style="display:none">
                                    				<strong>Success!</strong> Account Deal Information is saved successfully!
                                    			</div>
                                    			<div class="alert alert-danger" style="display:none">
                                    				<strong>Error!</strong> You have some form errors. Please check below.
                                    			</div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                            <label class="control-label"> Deal Code <span class="required"> * </span></label>
                                                            <input type="text" name="ad_deal_code" id="AD_DEAL_CODE" class="form-control" required="required" maxlength="15"  value="{{ $deal_info->ad_deal_code }}" />
                                                        </div>
                                                </div>
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Title <span class="required"> * </span></label>
                                                        <input type="text" name="ad_deal_title" id="AD_DEAL_TITLE" class="form-control" required="required" maxlength="255"  value="{{ $deal_info->ad_deal_title }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Deal Owner </label>
                                                        <select class="bs-select form-control" name="ad_deal_owner" id="AD_DEAL_OWNER" data-actions-box="true">
                                                                <option value="0"> Owner </option>
                                                                @foreach ($lst_users as $key => $user_info )
                                                                        <option {{ $deal_info->ad_deal_owner == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Lead </label>
                                                        <select class="bs-select form-control" name="fk_lead_id" id="FK_LEAD_ID" data-actions-box="true">
                                                                <option value="0"> Lead </option>
                                                                @foreach ($lst_leads as $key => $lead_info )
                                                                        <option value="{{ $lead_info->cl_id }}" {{ $deal_info->fk_lead_id  == $lead_info->cl_id ? "selected" : "" }} >{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name  }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Contact </label>
                                                        <select class="bs-select form-control" name="fk_contact_id" id="FK_CONTACT_ID" data-actions-box="true">
                                                                <option value="0"> Contact </option>
                                                                @foreach ($lst_contacts as $key => $cc_info )
                                                                        <option value="{{ $cc_info->cc_id }}" {{ $deal_info->fk_contact_id  == $cc_info->cc_id ? "selected" : "" }} >{{ $cc_info->cc_first_name . " " . $cc_info->cc_last_name  }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Account </label>
                                                        <select class="bs-select form-control" name="fk_account_id" id="FK_ACCOUNT_ID" data-actions-box="true">
                                                                <option value="0"> Account </option>
                                                                @foreach ($lst_accounts as $key => $account_info )
                                                                        <option value="{{ $account_info->ca_id }}"  {{ $deal_info->fk_account_id  ==  $account_info->ca_id ? "selected" : "" }}>{{ $account_info->ca_account_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Amount <span class="required"> * </span></label>
                                                        <input type="text" name="ad_deal_amount" id="AD_DEAL_AMOUNT" class="form-control" required="required" maxlength="255"  value="{{ $deal_info->ad_deal_amount }}" />
                                                    </div>
                                                </div>
                                                 <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Deal Close Date <span class="required"> * </span></label>
                                                        <input type="text" name="ad_closing_date" id="AD_CLOSING_DATE" class="form-control" required="required" maxlength="15" readonly="readonly"  value="{{ $deal_info->ad_closing_date }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Deal Stage </label>
                                                        <select class="bs-select form-control" name="ad_deal_stage" id="AD_DEAL_STAGE" data-actions-box="true">
                                                                <option value="0"> Deal Stage </option>
                                                                @foreach ($lst_deal_stages as $key => $ds_info )
                                                                        <option {{ $deal_info->ad_deal_stage == $ds_info->cs_id ? "selected" : "" }} value="{{ $ds_info->cs_id }}">{{ $ds_info->cs_stage_name }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label> Deal type </label>
                                                        <select class="bs-select form-control" name="ad_deal_type" id="AD_DEAL_TYPE" data-actions-box="true">
                                                                <option value="0"> Deal Type </option>
                                                                <option {{ $deal_info->ad_deal_type == 1 ? "selected" : "" }} value="1"> Existing Business </option>
                                                                <option {{ $deal_info->ad_deal_type == 2 ? "selected" : "" }} value="2"> New Business  </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                    <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Probability (%)</label>
                                                        <input type="text" name="ad_deal_probability" id="AD_DEAL_PROBABILITY" class="form-control"  maxlength="5"  value="{{ $deal_info->ad_deal_probability }}" />
                                                    </div>
                                                </div>
                                                    <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label">Expected Revenue</label>
                                                        <input type="text" name="ad_expected_revenue" id="AD_EXPECTED_REVENUE" class="form-control"  maxlength="50"  value="{{ $deal_info->ad_expected_revenue }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Deal Next Step </label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="AD_NEXT_STEP"  class="form-control" name="ad_next_step"  cols="">{{ $deal_info->ad_next_step }}</textarea>
                                                     </div>
                                                </div>
                                                <div class="col-md-12">
                                                     <div class="form-group">
                                                        <label class="control-label"> Deal Description</label><br/>
                                                        <textarea style="width:100%;height:250px;resize:none" id="AD_DEAL_DESCRIPTION"  class="form-control" name="ad_deal_description"  cols="">{{ $deal_info->ad_deal_description }}</textarea>
                                                     </div>
                                                </div>
                                            </div>
                                           <div class="row" style="height:5px;"></div>
                                            <div class="row">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3" align="right">
                                                     <button type="submit" name="btn_save_deal" id="BTN_SAVE_DEALS"  class="btn btn-info">Save</button>
                                                    <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
							</div>
					   </div>

@endsection