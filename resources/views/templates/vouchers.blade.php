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

<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="well col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
            <div class="row">
            	<div class="col-md-12">
            		<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                                        <em>Date: %voucher_date%</em>
                                    </p>
                                    <p>
                                        <em>Voucher #: %voucher_number%</em>
                                    </p>
                                </div>
            				</td>
            			</tr>
            		</table>
            	</div>
            </div>
            <div class="row">
                <div class="text-center">
                    <h1>Paid</h1>
                    %receipt_table%
                </div> 
                	
            </div>
        </div>
    </div>
</div>