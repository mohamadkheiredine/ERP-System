<?php
/***********************************************************
invoices.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
#invoice{
    padding: 30px;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px
}

.invoice header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #3989c6
}

.invoice .company-details {
    text-align: right
}

.invoice .company-details .name {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .contacts {
    margin-bottom: 20px
}

.invoice .invoice-to {
    text-align: left
}

.invoice .invoice-to .to {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .invoice-details {
    text-align: right
}

.invoice .invoice-details .invoice-id {
    margin-top: 0;
    color: #3989c6
}

.invoice main {
    padding-bottom: 50px
}

.invoice main .thanks {
    margin-top: -100px;
    font-size: 2em;
    margin-bottom: 50px
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #3989c6
}

.invoice main .notices .notice {
    font-size: 1.2em
}
 

.invoice footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0
}

@media print {
    .invoice {
        font-size: 11px!important;
        overflow: hidden!important
    }

    .invoice footer {
        position: absolute;
        bottom: 10px;
        page-break-after: always
    }

    .invoice>div:last-child {
        page-break-before: always
    }
}
</style>
<!--Author      : @arboshiki-->
<div id="invoice">

    <div class="toolbar hidden-print">
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col-md-12">
                    	<table border="0" width="100%" style="background-color:white;">
                    		<tr>
                    			<td width="50%"> <img src="%logo_image_url%" style="width:80px;" data-holder-rendered="true" /></td>
                    			<td width="50%">
                    					<div class="company-details">
                                		 	<h2 class="name">
                                                %company_name%
                                            </h2>
                                            <div>%company_address%</div>
                                            <div>%company_phone%</div>
                                            <div>%company_email%</div>
                                       </div>
                    			</td>
                    		</tr>
                    	</table>
                        
                    </div> 
                </div>
            </header>
            <main>
            	 <div class="row">
                    <div class="col-md-12">
                    	<table border="0" width="100%" style="background-color:white;">
                    		<tr>
                    			<td width="50%">
                    				<div class="col-md-6 invoice-to">
                                        <div class="text-gray-light">INVOICE TO:</div>
                                        <h2 class="to">%client_name%</h2>
                                    </div>
                    			</td>
                    			<td width="50%">
                    					 <div class="col-md-6 invoice-details">
                      					  <h4 class="invoice-id">INVOICE #%invoice_code%</h4>
                                        <div class="date">Date of Invoice: %creation_date%</div>
                                        <div class="date">Due Date: %due_date%</div>
                                    </div>
                    			</td>
                    		</tr>
                    	</table>
                        
                    </div> 
            	</div> 
                <div class="row">
                	<div class="col">
                		 %item_table%
                	</div>
                </div>
                <div style="width:100%;height:20px;">&nbsp;</div> 
            </main>
             
        </div>
        <div></div>
    </div>
</div>