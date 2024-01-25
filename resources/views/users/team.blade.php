<?php
/***********************************************************
team.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 28, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Users Team Management"])

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
<script type="text/javascript" src="{{ url('js/modules/team.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/users/teams.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">User Teams</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
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
						<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input m-input--solid" placeholder="Search..." id="generalSearch">
								<span class="m-input-icon__icon m-input-icon__icon--right">
									<span>
										<i class="la la-search"></i>
									</span>
								</span>
							</div>

						</div>
						<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
						<div class="col-md-4">
                            <div class="d-md-none m--margin-bottom-10"></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<div class="m-separator m-separator--dashed d-xl-none"></div>
				</div>
			</div>
		</div>
		<!--end: Search Form -->
          <!--begin: Datatable -->
		<div class="m_datatable">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>ID</th>
						<th>Team Name</th>
						<th>Number of Members</th>
						<th>edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody  class="LstTeamsGrid"></tbody>
			</table>
		</div>
		<!--end: Datatable -->
		<div class="row">
			<div class="col-md-10"></div>
			<div class="col-md-2">
					<a href="{{ url('administrator/team/addform') }}" class="btn btn-info">
						<span>
							<i class="fas fa-user"></i>
							<span>
								New Team
							</span>
						</span>
					</a>
			</div>
		</div>
    </div>
 </div>
 
@endsection