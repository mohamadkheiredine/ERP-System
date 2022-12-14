<?php
/***********************************************************
lsttransactiondetails.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 9, 2021
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2021

Page Description :

***********************************************************/

?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>
			Enterprise Resource Planning - Transaction Details
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<!--end::Web font -->
		<!--end::Page Vendors -->
		<link href="{{ url('default/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ url('default/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		<link rel="shortcut icon" href="{{ url('favicon.ico') }}" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		
	</head>
	<body>
		<div class="row" style="height: 10px;"><div class="col-md-12">&nbsp;</div></div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<table class="table table-bordered table-hover">
                	<thead>
                		<tr>
                			<th style="width:23%">Account Payable</th>
                			<th style="width:23%">Account Receivable</th>
                			<th style="width:30%">Label Operation</th>
                			<th style="width:10%">Debit</th>
                			<th style="width:10%">Credit</th>
                			<th style="width:10%">Currency</th> 
                		</tr>
                	</thead>
                	<tbody id="TRANS_MOVEMENTS" >
                		@foreach($lst_movements as $index => $movement_info )
                		<?php 
                		//if(!isset($accounts_array[ $movement_info->tm_ledger_account ]))
                		//    continue;
                		?>
                		<tr>
                			<td>{{ (  $movement_info->tm_ledger_account != '' && $movement_info->tm_ledger_account != 0 ) ? $movement_info->Payable->aa_account_ref . " - " . $movement_info->Payable->aa_account_label : "" }}</td>
                			<td>{{ ( $movement_info->tm_sub_ledger_account != '' ) ?  $movement_info->Receivable->aa_account_ref . " - " . $movement_info->Receivable->aa_account_label : "" }}</td>
                			<td>{{ $movement_info->tm_ledger_label }}</td>
                			<td>{{ number_format($movement_info->tm_debit,2) }}</td>
                			<td>{{ number_format($movement_info->tm_credit,2) }}</td> 
                			<td><b>{{ $movement_info->currency->cc_currency_code }}</b></td>
                		</tr>
                		@endforeach
                	</tbody>
                </table>	
			</div>
			<div class="col-md-2"></div>
		</div>		 
	</body>
</html>
