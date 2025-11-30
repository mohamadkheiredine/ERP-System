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



        /* Make tbody fill the available A4 printable area */
        .invoice-table tbody {
            display: block;
            min-height: 800px; /* A4 remaining height – adjust if needed */
            position: relative;
        }

        /* Remove all row borders */
        .invoice-table tbody td {
            border-top: none !important;
            border-bottom: none !important;
            border-left: solid 1px #000;
        }

        /* Header & footer table sections remain normal */
        .invoice-table thead,
        .invoice-table tfoot {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .invoice-table tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        /* Create FULL-HEIGHT vertical column borders */
        .invoice-table tbody::before {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;      /* stretch full height */
            left: 0;
            right: 0;
            z-index: 0;

            /* 6 columns = 5 divider lines */
            background:
                linear-gradient(to bottom, #000 0%, #000 100%) 0% 0 / 1px 100% no-repeat,
                linear-gradient(to bottom, #000 0%, #000 100%) 20% 0 / 1px 100% no-repeat,
                linear-gradient(to bottom, #000 0%, #000 100%) 40% 0 / 1px 100% no-repeat,
                linear-gradient(to bottom, #000 0%, #000 100%) 60% 0 / 1px 100% no-repeat,
                linear-gradient(to bottom, #000 0%, #000 100%) 80% 0 / 1px 100% no-repeat,
                linear-gradient(to bottom, #000 0%, #000 100%) 100% 0 / 1px 100% no-repeat;
        }

        /* Ensure table cells layout above the background */
        .invoice-table tbody td {
            position: relative;
            z-index: 2;
        }




    </style>
</head>
<body>
    <div class="invoice-container">
        <header class="invoice-header">
            <div class="company-details">
                <h2><u>%company_name%</u></h2>
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


            <div style="width:100%;position:fixed;bottom:-110px;font-size:18px;padding-top:20px;display:flex;justify-content:space-between;text-align:center;margin-top:25px;padding-top:10px;">
                <span><strong>Created By:</strong> %CREATED_BY%</span>
                <span><strong>Printed By:</strong> %PRINTED_BY%</span>
                <span><strong>Print Date:</strong> %PRINT_DATE%</span>
            </div>
        </footer>
    </div>
</body>
</html>
