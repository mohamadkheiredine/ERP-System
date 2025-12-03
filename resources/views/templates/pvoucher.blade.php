<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: white;
        }

        .a4-container {
            width: 310mm;
            min-height: 397mm;
            margin: 0 auto;
            background: white;
            padding: 20mm;
            position: relative;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .header p {
            margin: 3px 0;
            font-size: 14px;
        }

        .voucher-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .voucher-title h1 {
            font-size: 28px;
            text-decoration: underline;
        }

        .doc-date {
            float: right;
            text-align: right;
            margin-bottom: 20px;
        }

        .doc-date div {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .details {
            clear: both;
            margin-bottom: 30px;
        }

        .details div {
            margin-bottom: 12px;
            font-size: 14px;
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
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .footer-section {
            position: absolute;
            bottom: 20mm;
            left: 20mm;
            right: 20mm;
            width: calc(100% - 40mm);
        }

        .signature {
            width:100%;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-top: 60px;
            margin-top: 200px;
        }
        .signature ul{
            list-style-type: none;
            width: 100%;
            position: relative;
        }
        .signature ul li {
            text-align: center;
            width: 23%;
            font-size: 14px;
            float: left;

        }

        .print-info {
            display: flex;
            width: 100%;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            position: fixed;
            bottom: -20px;
        }

        .print-info ul {
            width: 100%;
            position: relative;
        }

        .print-info ul li{
            width: 32%;
            float: left;
            list-style-type: none;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                background: white;
            }

            .a4-container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 20mm;
            }

            .footer-section {
                position: absolute;
                bottom: 20mm;
                left: 20mm;
                right: 20mm;
            }
        }
    </style>
</head>
<body>
<div class="a4-container">
    <div class="header">
        <h1>%company_name%</h1>
        <p>%company_address%</p>
        <p>Phone: %company_phone%</p>
    </div>

    <div class="voucher-title">
        <h1>Payment Voucher</h1>
    </div>

    <div class="doc-date">
        <div>Doc. #: <strong>%voucher_code%</strong></div>
        <div>Date: <strong>%payment_date%</strong></div>
        <div>VAT Value: <strong>0.00</strong></div>
    </div>

    <div class="details">
        <div>Paid To: <strong>%account_ledger_to%</strong></div>
        <div>Account #: <strong>%account_to%</strong></div>
        <div>The Amount of: <strong>%voucher_amount% %voucher_currency%</strong> (%voucher_amount_letters% %voucher_currency% ONLY)</div>
        <div>Being For: <strong>%voucher_description%</strong></div>
    </div>

    <table aria-label="Payment details including amount, currency, payment mode, and value date">
        <thead>
        <tr>
            <th scope="col">Amount</th>
            <th scope="col">Curr.</th>
            <th scope="col">Pay From</th>
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

    <div class="signature">
        <ul>
            <li>Prepared By:</li>
            <li>Received By:</li>
            <li>Management:</li>
            <li>Accounting:</li>
        </ul>
    </div>
    <br/>
    <br/>
    <div class="footer-section">
        <div class="print-info">
            <ul>
                <li>
                    <span><strong>Created By:</strong> %CREATED_BY%</span>
                </li>
                <li>
                    <span><strong>Printed By:</strong> %PRINTED_BY%</span>
                </li>
                <li>
                    <span><strong>Print Date:</strong> %PRINT_DATE%</span>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
