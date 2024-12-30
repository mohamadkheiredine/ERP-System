<?php
/***********************************************************
closureapp
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 10, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


{
    $dt = date('Y-m-d');
}
?>


@extends('layouts.layout',['page_title' => "Call Center Management"])

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
<script type="text/javascript" src="{{ url('js/modules/callapt.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/closurereport.js') }}"></script>
@endsection

@section('content')
<div class="card shadow-sm">
	<div class="card-header">
		<h3 class="card-title">Closure Sales Appointments Report</h3>
		<div class="card-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-danger dropdown-toggle"
					data-bs-toggle="dropdown" aria-expanded="false">Action</button>
				<ul class="dropdown-menu">
                                    <li><a class="dropdown-item QuickAction" data-action_type="DOWNLOAD_PDF" href="#">Download PDF</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="card-body">
         
            
            <div class="row">
               <div class="col-md-4">
                    <div class="form-group">
                      <label>Salesman <span class="required"> * </span> </label>
                      <select name="cl_sales_id" required="required" id="CL_SALES_ID"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
                              <option value="">-- Select User --</option>
                              <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                      <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                              <?php  } ?>
                      </select>
                  </div>
              </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>From </label>
                        <input type="text" class="form-control" readonly="readonly" name="ca_apt_from_date" id="CA_APT_FROM_DATE" value="{{ date("Y-m-01", strtotime($dt)) }}" />
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>To </label>
                        <input type="text" class="form-control" readonly="readonly" name="ca_apt_last_date" id="CA_APT_LAST_DATE" value="{{ date("Y-m-t", strtotime($dt)) }}" />
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12" style="height:10px">&nbsp;</div>
            </div>
            <div class="row">
                <div style="text-align:center" id="LstPercentageClosure" class='col-md-12'>
                </div>
            </div>  
	</div>
</div>

@endsection