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
<html>
<head>
        <meta charset="UTF-8">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<style>
#invoice{
    padding: 30px;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 3
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
    margin-bottom: 0;
	font-size: 24px;
	color:black;
	font-family: tahoma;
}

.invoice .company-details div{
    font-family:tahoma;
}

.invoice .contacts {
    margin-bottom: 20px
}

.invoice .invoice-to {
    text-align: left
}

.invoice .invoice-to .to {
    margin-top: 0;
    margin-bottom: 0;
	font-size:18px;
	font-weight: bold;
}

.invoice .invoice-details {
    text-align: right
}

.invoice-id {
    margin-top: 0;
	font-family:tahoma;
	font-size:18px;
    color: #3989c6
}
.date{
	text-align: left;
	font-size: 18px;
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
.tablesection{
	margin-top: 10px;
}
.tablesection th{
	border:solid 1px black;
	height: 50px;

}
.tablesection td{
	border:solid 1px black;
}
.InvoiceDescription{
	height: 100px;
	margin-top: 10px;
	font-size:14px;
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
	</head>
	<body>
<div id="invoice">
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col-md-12">
                    	<table border="0" width="100%" style="background-color:white;height: 125px;" cellspacing="0" cellpadding="0">
                    		<tr>
                    			<td width="50%"></td>
                    			<td width="50%">
                    					<div class="company-details">
                                		 	<h2 class="name">
                                                %company_name%
                                            </h2>
                                            <div>%company_address%</div>
                                            <div>%company_phone%</div>
                                            <div>%company_email%</div>
                                            <div><strong>CR:</strong>%registration_number%</div>
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
                    			<td align="center" width="100%" colspan="2" style="border:solid 1px black"  class="invoice-id">
                    				 #%invoice_code%
                    			</td>
                    		</tr>
                    		<tr>
                    			<td width="50%" style="border:solid 1px black">
                    				<div class="col-md-6 invoice-to">
                                        <div class="text-gray-light">INVOICE TO:</div>
                                        <h6 class="to">%client_name%</h6>
                                    </div>
                    			</td>
                    			<td width="50%" style="text-align: left;border:solid 1px black">
                    					 <div class="col-md-6 invoice-details">
                                        <div class="date">Date of Invoice: %creation_date%</div>
                                    </div>
                    			</td>
                    		</tr>
                    	</table>
                    </div>
            	</div>
                <div class="row InvoiceDescription" style="margin-bottom: 80px;margin-top:40px">
                <div class="col-md-12" style="height: 10px;">&nbsp;</div>
                    <div class="col-md-12">
                    	<h4><u>Description:</u></h4>
                    </div>
                	<div class="col-md-12" style="margin-top:10px" align="left">
                		 %invoice_description%
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
	</body>
</html>
