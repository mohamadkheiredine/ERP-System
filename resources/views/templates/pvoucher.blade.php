<?php
/***********************************************************
pvoucher
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 30, 2024
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
    <title>Payment Voucher</title>
    <style>
         body {
            font-family: Arial, sans-serif;
        }
        .container {
            padding: 10px;
            max-width: 100%;
        }
        .header {
            text-align: left;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 2px;
        }
        .details {
            margin-bottom: 30px;
        }
        .details div {
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table, th, td {
            border: 1px solid black;
        }
        
        th, td {
            padding: 10px;
            text-align: center;
        }
        
        .signature {
            display: flex;
            margin-top: 30px;
        }
        .signature div {
            text-align: center;
            float:left;
            width:25%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>%company_name%</h1>
            <h1 style="float:right;">%company_name_translation%</h1>
            <p>%company_address%</p>
            <p>Phone: %company_phone%</p>
        </div>
         <div style="width:100%;text-align: center">
            <h3>Payment Voucher</h3></div>
        <div class="details">
            <div>Paid To: <strong>%account_ledger_to%</strong></div>
            <div>Account #: <strong>%account_to%</strong></div>
            <div>Doc. #: <strong>%voucher_code%</strong></div>
            <div>Date: <strong>%payment_date%</strong></div>
            <div>The Amount of: <strong>%voucher_amount% %voucher_currency%</strong> (%voucher_amount_letters% %voucher_currency% ONLY)</div>
            <div>VAT Value: <strong>0.00</strong></div>
            <div>Being For: <strong>%voucher_description%</strong></div>
        </div>
        <table aria-label="Payment details including amount, currency, payment mode, and value date">
            <thead>
                <tr>
                    <th scope="col">Amount</th>
                    <th scope="col">Curr.</th>
                    <th scope="col">Payment Mode</th>
                    <th scope="col">Value Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>%voucher_amount%</td>
                    <td>%voucher_currency%</td>
                    <td>%voucher_from%</td>
                    <td>%payment_date%</td>
                </tr>
            </tbody>
        </table>
        <div style="width:100%;height:200px">&nbsp;</div>
        <div class="signature">
            <div>Prepared By: _____________________<br></div>
            <div>Received By: _____________________<br></div>
            <div>Management: _____________________<br></div>
            <div>Accounting: _____________________<br></div>
        </div>
    </div>
</body>
</html>