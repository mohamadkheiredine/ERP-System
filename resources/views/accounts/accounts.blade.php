<?php
/***********************************************************
accounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 23, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


?>

@extends('layouts.layout',['page_title' => "Accounts Management"])

@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/clients.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/clients.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Accounts Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" data-action_type="IMPORT" href="#">Import List Accounts</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
                <span id="hidden_fields">
            <input type="hidden" name="page_number" value="1" />
        </span>
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
												</div>
												<div class="col-md-4">
                                                                                                     @if(Config::get('appconfig.crm_telemarketing') == '0')
                                                                                                    <select class="bs-select form-control" id="ACCOUNT_CATEGORIES" name="account_category">
                                                                                                                 <option value="0">-- Select Category --</option>
                                                                                                         @foreach($lst_client_categories as $index => $cc_info)
                                                                                                           <option value="{{ $cc_info->cc_id }}">{{  $cc_info->cc_category_ref . " - " . $cc_info->cc_category_name }}</option>
                                                                                                         @endforeach
                                                                                                     </select>
                                                                                                     @endif
												</div>
											</div>
										</div>
										<div class="col-xl-4 order-1 order-xl-2 align-right">
											<a href="{{ url('crm/clients/addform') }}" class="btn btn-info">
												<span>
													<i class="fas fa-user"></i>
													<span>
														New Client
													</span>
												</span>
											</a>

										</div>
									</div>
								</div>
								 
								<div class="table-responsive">
                                                      		<table class="table table-row-dashed table-row-gray-300 gy-7">
                                                                    <thead>
                                                                            <tr class="fw-bold fs-6 text-gray-800">
                                                                                    <th title="#">#</th>
                                                                                    <th title="Id"> ID </th>
                                                                                    <th title="Client name"> Client Code </th>
                                                                                    <th title="Client name"> Client Name </th>
                                                                                    <th title="Mobile"> Mobile </th>
                                                                                    <th title="Email"> Email </th>
                                                                                    <th title="Email"> Full Address </th>
                                                                                    <th style="width:2px;" nowrap title="#"> edit </th>
                                                                                    <th style="width:2px;" nowrap title="#"> Delete </th>
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody  id="LstClients">

                                                                    </tbody>
                                                        </table>
								</div> 
        
                      <div class="row">
                  <div class="col-md-12" style="height:50px" align="right"></div>
              </div>
            <div class="row">
                <div class="col-md-10 col-lg-10 col-xs-10" align="left">
                    <ul id="AccountsPagination" class="pagination-sm"></ul>
                </div>
                  <div class="col-md-2 col-lg-2 col-xs-2" align="right"></div>
              </div>
              <div class="row">
                  <div class="col-md-12" style="height:50px" align="right"></div>
              </div>
    </div>
</div>
 <div id="ImportClientsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ImportModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="ImportModalLabel">Import Excel Sheet</h3>
    </div>
    <div class="modal-body">
        <form name="frm_import_accounts" id="FRM_IMPORT_ACCOUNTS">
              <span id="hidden_fields">
                      {!! csrf_field() !!}
                    </span>
            <div class="row">
                        <div class="col-md-4">
                              <div class="form-group">
                                <label>select File <span class="required"> * </span> </label>
                                <input type="file" name="ac_temp_file" class="form-control" />
                            </div>
                        </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
    </div>
</div>
@endsection