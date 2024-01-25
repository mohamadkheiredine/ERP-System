<?php
/***********************************************************
accountingjournals.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Accounting Journals
***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Accounting Journals"])

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
<script type="text/javascript" src="{{ url('js/modules/accountingjournals.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/accounting/accountingjournals.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Accounting Journals</h3>
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
    <!--begin: Search Form -->
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
                                                 
												</div>
											</div>
										</div>
										<div class="col-xl-12 order-1 order-xl-12 m--align-right">
										<div class="separator my-10"></div>
										</div>
									</div>
								</div>
								<!--end: Search Form -->
		                          <!--begin: Datatable -->
								<div class="table-responsive">
									 <table class="table table-rounded table-striped border gy-7 gs-7" width="100%">
                                    		<thead>
                                    			<tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    				<th title="Id" style="white-space: nowrap;">ID</th>
                                    				<th title="code" style="white-space: nowrap;">Journal Code</th>
                                    				<th title="label" style="white-space: nowrap;">Label</th>
                                    				<th title="type" style="white-space: nowrap;">Type</th>
                                    				<th title="Active" style="white-space: nowrap;">Active</th>
                                    			</tr>
                                    		</thead>
                                    		<tbody  id="LstAccountJournals">
                                    
                                    
                                    
                                    			</tbody>
                                    </table>
								</div>
								<!--end: Datatable -->
								<div class="col-xl-12 order-1 order-xl-12 m--align-right" style="margin-top: 12px;">
									<div class="m-separator m-separator--dashed d-xl-none"></div>
								</div>
    </div>
</div>

<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="ImportsModallLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="ImportsModallLabel">
											Import Journals
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>
									</div>
									<div class="modal-body">
										<form name="frm_import_journals" id="FRM_IMPORT_JOURNALS">
											   {!! csrf_field() !!} 
											<div class="form-group">
												<label for="message-text" class="form-control-label">
													CSV File :
												</label>
												<input type="file" name="csv_file" class="form-control" />
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button type="button" name="btn_import_journals" id="BTN_IMPORT_JOURNALS" class="btn btn-primary">
											Import Journals
										</button>
									</div>
								</div>
							</div>
						</div> 
@endsection