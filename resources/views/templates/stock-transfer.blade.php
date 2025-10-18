<?php
/***********************************************************
stock-transfer
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Warehouse Stock Transfer Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .company-info {
            text-align: right;
        }
        .transfer-details {
            margin: 10px 0;
            border: 1px solid #ddd;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            border-top: 1px solid #333;
            padding-top: 20px;
        }
		.logo h1 img{
			width:75px;
			height:auto;
		}
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <h1><img src=%COMPANY_LOGO% /></h1>
        </div>
        <div class="company-info">
            <h2>%STOCK_TRANSFER_LABEL%</h2>
            <p>Transfer No: %STOCK_TRANSFER_CODE%</p>
            <p>Date: %STOCK_TRANSFER_DATE%</p>
        </div>
    </div>

    <div class="transfer-details">
        <h3>Transfer Details</h3>
        <p><strong>From Warehouse:</strong> %STOCK_SOURCE_WAREHOUSE%</p>
        <p><strong>To Warehouse:</strong>%STOCK_DESTINATION_WAREHOUSE%</p>
        <p><strong>Transfer Reason:</strong>%STOCK_TRANSFER_DESCRIPTION%</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
           %TRANSFER_LST_PRODUCTS%
        </tbody>
    </table>

    <div class="totals">
        <p><strong>Total Items Transferred:</strong> %TOTAL_TRANSFER_QUANTITY%</p>
    </div>

    <div class="signatures">
        <table cellpadding="0" cellspacing="0" border="0" style="border:solid 0px #c0c0c0">
            <tr>
                <td style="text-align: left;border:solid 0px #c0c0c0">
                    <div>
                        <p>Issued By:</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    </div>
                </td>
                <td style="text-align: left;border:solid 0px #c0c0c0">
                    <div>
                        <p>Received By:</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    </div>
                </td>
            </tr>
        </table>


    </div>
</body>
</html>
