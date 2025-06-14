<?php
/************************************************************
roles.blade.php
Product :
Version : 1.0
Release : 0
Date Created : Aug 7, 2015
Developed By  : Mohamad. Mantach  PHP Department Softweb S.A.R.L
All Rights Reserved, Softweb S.A.R.L COPYRIGHT 2015

Page Description :
Page of roles management where we can add/edit and delete roles
************************************************************/

?>

@extends('layouts.layout',['page_title' => "Roles Management"])

@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/roles.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/roles/rolesmanagement.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Print</a></li>
                <li><a class="dropdown-item" href="#">Export as CSV</a></li>
                <li><a class="dropdown-item" href="#">Download Import Template</a></li>
                <li><a class="dropdown-item" href="#">Import</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
    	<div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
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
				<div class="col-xl-4 order-1 order-xl-2" style="text-align: right">
					<a href="{{ url('roles/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Role
							</span>
						</span>
					</a>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-md-12" style="height:10px">&nbsp;</div>
        </div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="row table">
            <div class="table-responsive">
                <table class="table table-rounded table-striped border gy-7 gs-7">
                    <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th style="width:2px"><input type="checkbox" name="ck_all_roles" id="CK_ALL_ROLES" value="1" /></th>
                        <th style="width:2px">#</th>
                        <th>Role</th>
                        <th>Description</th>
                        <th style="width:2px" nowrap>Edit</th>
                        <th style="width:2px" nowrap>Delete</th>
                    </tr>
                    </thead>
                    <tbody id="ListRoleGirds">

                    </tbody>
                </table>
            </div>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-12" align="right">
			<a href="{{ url('roles/addform') }}" class="btn btn-info m-btn">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Role
							</span>
						</span>
					</a>
					<div class="m-separator m-separator--dashed d-xl-none"></div>
			</div>
			<div class="col-xl-4 order-1 order-xl-2 m--align-right">

				</div>
		</div>
    </div>

    </div>

@endsection
