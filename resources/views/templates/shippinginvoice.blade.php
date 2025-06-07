<?php
/***********************************************************
 * shippinginvoice.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/31/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>

    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 40px; background: #fff; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            border-radius: 10px;
            background: #fff;
        }
        .header-table {
            width: 100%;
        }
        .header-table td {
            vertical-align: top;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
        }
        .logo {
            text-align: right;
        }
        .info-table {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .info-table td {
            vertical-align: top;
            font-size: 16px;
            padding: 2px 0;
        }
        .bold { font-weight: bold; }
        .section {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .desc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .desc-table th, .desc-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 15px;
        }
        .desc-table th {
            background: #eee;
        }
        .desc-table .total-row {
            font-weight: bold;
            background: #fafafa;
        }
        .desc-table .subtotal-row td {
            border-top: 2px solid #000;
        }
        .right { text-align: right; }
        .summary-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
            margin-top: 0px;
            font-size: 16px;
        }
        .summary-table td {
            padding: 4px 8px;
        }
        .summary-table .bold {
            font-weight: bold;
        }
        .summary-table .grand-total {
            font-size: 18px;
            border-top: 2px solid #000;
        }
        .amount-due {
            font-size: 26px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .thankyou {
            text-align: center;
            font-size: 15px;
            margin-top: 30px;
        }
        a { color: #000; text-decoration: underline;}
    </style>
</head>
<body>
<div class="invoice-box">
    <table class="header-table">
        <tr>
            <td class="company-name">
                SKY CARGO SERVICES<br>
                Chiyah, Lebanon<br>
               skycargolb@gmail.com<br>
                +961 71 442 276<br>
                +90 545 218 55 71
            </td>
            <td class="invoice-title">INVOICE</td>
            <td class="logo">
                <!-- Replace with actual logo image if available -->
                <img src="%LOGOURL%" alt="Logo" width="100">
            </td>
        </tr>
    </table>
    <table class="info-table">
        <tr>
            <td>
                <div class="bold">INVOICE TO</div>
                <span class="bold">%CUSTOMERNAME%</span><br>
                %CUSTOMERCODE%<br>
                %CUSTOMERPHONE%<br>
                %CUSTOMERADDRESS%
            </td>
            <td>
                Departing from IST. <span class="right">April 20, 2024</span><br>
                Shipping Way <span class="right">Sea Freight</span><br>
                Invoice No <span class="right">236869</span><br>
                Invoice Date <span class="right">April 19, 2024</span>
            </td>
        </tr>
    </table>
    <div class="section">
        Hello, The goods will be received in Lebanon within at least 5 to 8 days after the shipment leaves Istanbul, and you will be notified of all the new.
    </div>
    <table class="desc-table">
        <tr>
            <th>DESCRIPTION</th>
            <th>NO</th>
            <th>WEIGHT</th>
            <th>PRICE</th>
            <th>TOTAL</th>
        </tr>
        %LISTPRODUCT%
        <tr class="total-row">
            <td>TOTAL SHIPPING</td>
            <td>1 P</td>
            <td>%TOTAL_WEIGHT%</td>
            <td></td>
            <td>%TOTAL_AMOUNT%</td>
        </tr>
    </table>
    <table class="summary-table">
        <tr>
            <td>Goods prices</td>
            <td class="right">%AMOUNT%</td>
        </tr>
        <tr>
            <td>Fee</td>
            <td class="right">%TOTAL_FEES%</td>
        </tr>
        <tr>
            <td>Delivery</td>
            <td class="right">%TOTAL_DELIVERY%</td>
        </tr>
        <tr class="grand-total">
            <td class="bold">Total</td>
            <td class="right bold">%TOTAL%</td>
        </tr>
        <tr>
            <td>AMOUNT PAID</td>
            <td class="right"></td>
        </tr>
        <tr>
            <td>%DELIVERY_DATE%</td>
            <td></td>
        </tr>
    </table>
    <div style="clear: both;"></div>
    <div class="amount-due">
        AMOUNT DUE <span style="float: right;"></span>
    </div>
    <div class="thankyou">
        Thank you very much. We really appreciate your business with us.
    </div>
</div>
</body>
</html>
