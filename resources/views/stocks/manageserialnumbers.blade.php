<?php
/***********************************************************
manageserialnumbers.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 3, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>


<html>
	<head>
				<meta charset="utf-8" />
		<title>
			ERP - Add Serial Numbers
		</title>
		<meta name="description" content="" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link rel="stylesheet" href="{{ url('default/assets/plugins/bootstrap/css/bootstrap.min.css') }}"  />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<style>
		  .LstSerialnumbers{
		  	list-style-type: none;
		  }
            .LstSerialnumbers li{
		  	list-style-type: none;
            	padding-right:10px;
            	padding-top: 10px;
		  }
		</style>
	</head>
	<body>
		<form name="frm_save_serialnumbers" id="FRM_SAVE_SERIALNUMBERS">
		<span id="hidden_fields">
			{!! csrf_field() !!}
			<input type="hidden" name="index" value="" />
		</span>
		<div class="container">
    		<div class="row">
    				<div class="col-md-12" style="height:20px;">
    					&nbsp;
    				</div>
    		</div>
			<div class="row">
				<div class="col-md-12">
					<ul class="LstSerialnumbers">
        				@foreach( $serial_numbers_array as $index => $serial_number )
        				<li>
        					<div class="row">
        						<div class="col-xs-10">
        						<input type="text" class="form-control SerialNumbers" name="serial_numbers[]" value="{{ $serial_number }}" />&nbsp;<a href='#'  class=DeleteCode' >
        						</div>
        						<div class="col-xs-2">
        						<a href="#" class="DeleteCode"><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a>
        						</div>
        						
        					</div>
        					
        				</li>
        				@endforeach
        				<li>
        					<div class="row">
        						<div class="col-xs-10">
        							<input type="text" class="form-control SerialNumbers" name="serial_numbers[]" value="" autofocus />
        						</div>
        						<div class="col-xs-2 DeleteSec">&nbsp;</div>
        					</div>
        				</li>
        			</ul>
				</div>
				<div class="cpl-md-10" align="right">
					<button type="button" name="btn_save_serialnumbers" class="btn btn-info">Save</button>
				</div>
				<div class="cpl-md-2"></div>
			</div>
		</div>
		</form>
		<script type="text/javascript" src="{{ url('default/assets/plugins/jquery.min.js') }}"></script>	
		<script type="text/javascript" src="{{ url('default/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ url('default/assets/plugins/jquery-scanner-detection/jquery.scannerdetection.js') }}"></script>
		<script type="text/javascript">
		$(function(){
			$('.DeleteCode').on('click',function(){
				$(this).parents('li').remove();
			});
			$('button[name=btn_save_serialnumbers]').on('click',function(){
				let lst_serial_numbers = new Array();
				$('.SerialNumbers').each(function(n){
					let SN = $(this).val();
					lst_serial_numbers.push(SN)
				})
				let quantity = lst_serial_numbers.length;
				let serial_numbers = lst_serial_numbers.join(',');
				window.opener.$('input[name=is_quanity]').val(quantity);
				window.opener.$('input[name=serial_ids]').val(serial_numbers);
				window.close()
			});

			$('.SerialNumbers:last').scannerDetection({
				timeBeforeScanTest: 200, // wait for the next character for upto 200ms
				avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
				preventDefault: true,

				endChar: [13],
				onComplete: function(barcode, qty){
			   		validScan = true;
			    	$('.SerialNumbers:last').val(barcode);
					// create a new row for adding a new barcode
			    	$('.SerialNumbers:last').removeAttr('autofocus');
					let clone_row = $('.LstSerialnumbers li:last').clone();
					clone_row.find('.SerialNumbers').val('');
					clone_row.find('.SerialNumbers').attr('autofocus',true);
					clone_row.find('.SerialNumbers').focus();
					clone_row.find('.DeleteSec').html("<a href='#'  class=DeleteCode' ><i class='fa fa-minus-circle' aria-hidden='true' height='16' ></i></a>");
					$('.LstSerialnumbers').append(clone_row);			    	
				
				},
				onError: function(string, qty){ 
				
				}
			});

		});
		</script>
	</body>
</html>