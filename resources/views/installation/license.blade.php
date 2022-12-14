<?php
/***********************************************************
license.blade.php
Product :
Version : 1.0
Release : 1
Date Created : May 1, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
generate license file and save it into the encrypted file on the 
main folder
***********************************************************/

?>
<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			ITM - ERP
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script> 
		<link href="{{ url('default/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ url('css/app.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ url('default/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ url('default/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" /> 
	</head>
	<body>
		<div class="container-fluid">
			<form name="frm_save_license" id="FRM_SAVE_LICENSE">
					 <span id="hidden_fields">
                        <input type="hidden" name="base_url" id="BASE_URL" value="{{ url('/') }}" />
                        {!! csrf_field() !!}
                    </span>
        		<div class="row">
        			<div class="col-md-12" align="center">
        				<div class="m-portlet">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title" align="center">
										<h3 class="m-portlet__head-text">
											Modules
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<div class="row">
									<div class="col-md-12">
										<table class="table">
													<thead>
														<tr>
															<th>#</th>
															<th>Module Name</th>
															<th>Enable Module</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<th scope="row"><img src="{{ url('icons/timesheet.png') }}" /></th>
															<td>Timesheet Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="TIMESHEET_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr> 
														<tr>
															<th scope="row"><img src="{{ url('icons/inventory.png') }}" /></th>
															<td>Invent Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="INVENTORY_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr> 
														<tr>
															<th scope="row"><img src="{{ url('icons/bills.png') }}" /></th>
															<td>Banking Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="BANKING_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/payroll.png') }}" /></th>
															<td>PayRoll Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="PAYROLL_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr> 
														<tr>
															<th scope="row"><img src="{{ url('icons/services.png') }}" height="64" /></th>
															<td>SRM Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="SRM_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr> 
														<tr>
															<th scope="row"><img src="{{ url('icons/accounting.png') }}" height="64" /></th>
															<td>Accounting Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="ACCOUNTING_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/bills.png') }}" height="64" /></th>
															<td>Billing Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="BILLING_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/manufacturing.png') }}" height="64" /></th>
															<td>Manufacturing Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="MANUFACTURING_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/production.png') }}" height="64" /></th>
															<td>Production Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="PRODUCTION_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/projects.png') }}" height="64" /></th>
															<td>Project Management Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="PROJECTS_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/sales.png') }}" height="64" /></th>
															<td>Sales Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="SALES_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/lead-automation.png') }}" height="64" /></th>
															<td>CRM Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="CRM_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/transfer-stock.png') }}" height="64" /></th>
															<td>Shipment Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="SHIPMENT_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
														<tr>
															<th scope="row"><img src="{{ url('icons/logistics.png') }}" height="64" /></th>
															<td>Logistics Module</td>
															<td>
																<span class="m-switch m-switch--lg">
																	<label>
																		<input type="checkbox" checked="checked" name="LOGISTICS_MODULE" value="1" />
																		<span></span>
																	</label>
																</span>
															</td> 
														</tr>
													</tbody>
												</table>
									</div>
								</div>
								<div class="m-separator m-separator--dashed"></div>
								<div class="row">
                        			<div class="col-md-12" align="right">
                        				<button type="button" name="btn_generate_license" id="BTN_GENERATE_LICENSE" class="btn btn-success">Generate License</button>
                        			</div>
                        		</div>
                        		<div class="row">
                        			<div class="col-md-12" align="right" style="height:40px;">
                        			</div>
                        		</div>
							</div>
						</div>
        			</div>
        		</div>
        		
    		</form>
		</div>
		<script src="{{ url('default/assets/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ url('default/assets/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
		<script src="{{ url('default/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
		<script src="{{ url('default/assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
		<script src="{{ url('default/assets/plugins/bootbox/bootbox.min.js') }}" type="text/javascript"></script>
		<script src="{{ url('default/assets/app/js/layout-builder.js') }}" type="text/javascript"></script>
		<script type="text/javascript">
			$(function(){
				$('#BTN_GENERATE_LICENSE').on('click',function(){
					var base_url = $('input[name=base_url]').val();
					var data = $('#FRM_SAVE_LICENSE').serialize();
					 $.ajax
		    	        ({
		    	            url : base_url + "/request/license/savelicenseinfo",
		    	            data : data, 
		    	            method : 'post',
		    	            dataType : "json",
		    	            success : function(response){
		    	              alert(response.error_msg);
		    	            }
		    	        });
				})
			})
		</script>
	</body>
	</html>