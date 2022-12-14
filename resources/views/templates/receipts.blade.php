<?php
/***********************************************************
receipts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
<html>
	<head>
	
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
.Description h5{
	font-family: tahoma;
	font-size:24px;
	text-decoration:underline; 
}
.Description span{
	font-family: tahoma;
	font-size:14px;
}
</style>
	</head>
	<body>
	
<div class="container">
    <div class="row">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
            	<div class="col-md-12">
            		<table width="100%" border="0" cellspacing="0" style="border:solid 1px black" cellpadding="0">
            			<tr>
            				<td style="width:50%;" align="left">
            					  <div class="col-xs-12 col-sm-12 col-md-12">
                                    <address>
                                        <strong>%company_name%</strong>
                                        <br>
                                        %company_address% 
                                        <br>
                                        <abbr title="Phone">P:</abbr> %company_phone% 
                                    </address>
                                </div>
            				</td>
            				<td style="width:50%;" align="right">
            				       <div class="col-xs-12 col-sm-12 col-md-12 text-right">
                                    <p>
                                        <em>Date: %receipt_date%</em>
                                    </p>
                                    <p>
                                        <em>Invoice #: %invoice_number%</em>
                                    </p>
                                    <p>
                                        <em>Receipt #: %receipt_number%</em>
                                    </p>
                                </div>
            				</td>
            			</tr>
            		</table>
            	</div>
            </div>
            <div class="row">
                <div class="text-left col-md-12 Description"> 
                    <h5>Description:</h5>
                    <span>%receipt_description%</span>
                </div>  
            </div>
            <div class="row">
                <div class="text-center"> 
                    %receipt_table%
                </div>  
            </div>
        </div>
    </div>
</div>
	</body>
</html>


<!------ Include the above in your HEAD tag ---------->
