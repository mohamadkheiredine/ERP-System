<?php

/***********************************************************
contractinvoice
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 22, 2024
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>

        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
}

.invoice-container {
    min-width: 1200px;
    margin: 0 auto;
}

.invoice-header {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #000;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.company-details h2 {
    font-size: 1.7em;
}

.company-details p{
    font-size: 24px;
}

.invoice-info h3 {
    font-size: 1.7em;
    text-align: right;
}

.client-info {
    margin-bottom: 20px;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.invoice-table th,
.invoice-table td {
    border: 1px solid #000;
    padding: 10px;
    text-align: left;
    font-size: 16px;
}

.totals {
    margin-bottom: 20px;
}

.totals p {
    text-align: right;
}

.invoice-footer {
    display: flex;
    justify-content: space-around;
    padding-top: 20px;
}

        .signature {
            display: flex;
            margin-bottom: 60px;
        }
        .signature div {
            text-align: center;
            float:left;
            width:23%;
            font-size: 22px;
            margin:4px;
            padding:4px;
        }



    </style>
</head>
<body>
    <div class="invoice-container">
        <header class="invoice-header">
            <div class="company-details">
                <h2><u>%company_name%</u></h2>
                <h2 style="float:right">%company_name_translation%</h2>
                <p>%company_address%</p>
                <p>Phone: %company_phone%</p>
                <p></p>
            </div>
            <div style="width:100%;text-align:center"><h3>INVOICE</h3></div>
            <div style='height:10px;'></div>

            <div style="width:100%;position: relative;height:120px;">
                <div style='width:60%;text-align:left;float: left;left:0px;position: absolute;height:60px;'>
                      <div class="invoice-info">
                        <p style="font-size: 20px"><strong>Number:</strong> %INVOICE_NUMBER%</p>
                        <p style="font-size: 20px"><strong>Date:</strong> %INVOICE_DATE%</p>
                        <p style="font-size: 20px"><strong>Curr:</strong> %INVOICE_CURRENCY%</p>
                        <p style="font-size: 20px"><strong>Sales</strong></p>
                        <p style="font-size: 20px"><strong>CR:</strong>%registration_number%</p>
                    </div>
                </div>
                <div style='width:35%;text-align:left;left:61%;float: right;position: absolute;height:60px;'>
                      <section class="client-info">
                        <p style="font-size: 20px"><strong>Number:</strong> %ACCOUNT_NUMBER%</p>
                        <p style="font-size: 20px"><strong>Client Name:</strong> %CLIENT_NAME%</p>
                        <p style="font-size: 20px"><strong>Client:</strong>  %CONTRACT_TYPE%</p>
                        <p style="font-size: 20px"><strong>Address:</strong> %CLIENT_ADDRESS%</p>
                        <p style="font-size: 20px"><strong>Phone:</strong> %CLIENT_PHONE%</p>
                    </section>
                </div>
            </div>
            <div style='clear:both;height:20px;'></div>
        </header>



        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>U.Price</th>
                    <th>Disc. %</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                %LST_CONTRACT_INVOICES%
                <tr style="height:950px">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="height:60px">
                    <td colspan="4" rowspan="2" style="text-align: left">
                        <p><strong>Amount:</strong> %INVOICE_COST% %INVOICE_CURRENCY%</p>
                    </td>
                    <td colspan="2">
                         <p><strong>Net Total:</strong> %INVOICE_TOTAL% %INVOICE_CURRENCY%</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                         <p><strong>In Words:</strong> %INVOICE_TOTAL_LETTERS% %INVOICE_CURRENCY%</p>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div style="height: 150px;width: 100%">&nbsp;</div>
        <footer class="invoice-footer">
         <div class="signature">
            <div>Stock Keeper:<br> ________________<br></div>
            <div>Representative:<br> ______________<br></div>
            <div>Manager:<br> ______________<br></div>
            <div>Client:<br> _______________<br></div>
        </div>

        </footer>
    </div>
</body>
</html>
