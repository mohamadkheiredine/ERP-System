<?php
/***********************************************************
converttoaccounts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 29, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Leads Management > Convert Leads To Accounts"])

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
<script type="text/javascript" src="{{ url('js/modules/leads.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/crm/converttoaccounts.js') }}"></script>
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Leads Management > Convert Leads To Accounts</h3>
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
            <span id="hidden_fields">
			<input type="hidden" name="cl_ids" value="{{ $cl_ids }}" />
			  {!! csrf_field() !!}
		</span>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="progress">
					<div class="progress-bar progress-bar-striped bg-danger" id="PROGRESSBAR" role="progressbar" style="width: 100%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
        </div>
    </div>
</div>
@endsection