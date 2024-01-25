<?php
/***********************************************************
contacts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 1, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Contacts Management 
***********************************************************/




?>

@extends('layouts.layout',['page_title' => "Leads Management"])

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
<script type="text/javascript" src="{{ url('js/modules/contacts.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/contacts/contacts.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Contact Management</h3>
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
    <div class="col-md-12">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
						<div class="position-relative w-md-400px me-md-2">
								<i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle ms-6">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
								<input type="text" class="form-control form-control-solid ps-10" name="general_search" id="generalSearch" value="" placeholder="Search" />
							</div>

						</div>
						<div class="col-md-4">
                            <div class="m-input-icon m-input-icon--left">
                            		<select class="bs-select form-control" id="CONTACT_LEAD" name="contact_lead">
                            			<option value="0">-- Select lead --</option>
                                        @foreach($lst_leads as $index => $lead_info)
                                          <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name . " " . $lead_info->cl_last_name }}</option>
                                        @endforeach
                                    </select>
                            </div>
						</div>
						<div class="col-md-4">&nbsp;</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ url('crm/contacts/addform') }}" class="btn btn-info">
						<span>
							<i class="flaticon-users"></i>
							<span>
								New Contact
							</span>
						</span>
					</a> 
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="table-responsive">
			<table class="table" id="ContactsDatatables" width="100%">
    		<thead>
    			<tr class="fw-bold fs-6 text-gray-800">
    				<th title="#">#</th>
    				<th title="Id"> ID </th>
    				<th title="Contact Name"> Contact Name </th>
    				<th title="Email"> Email </th>
    				<th title="Phone"> Phone </th>
    				<th title="Mobile"> Mobile </th> 
    				<th title="Fax"> Fax </th> 
    				<th title="Edit"> Edit </th> 
    				<th title="Delete"> Delete </th> 
    			</tr>
    		</thead>
    		<tbody  id="LstContacts">
    		
    		</tbody>
    </table>
		</div>
		<!--end: Datatable -->
    </div>
</div> 
@endsection