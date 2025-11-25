<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Voucher</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .a4-container {
            width: 210mm;
            min-height: 297mm;
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

        .receipt-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .receipt-title h1 {
            font-size: 28px;
            text-decoration: underline;
        }

        .doc-date-section {
            float: right;
            text-align: right;
            margin-bottom: 20px;
        }

        .doc-date-section div {
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
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-top: 60px;
        }

        .signature div {
            text-align: center;
            width: 23%;
            font-size: 14px;
        }

        .print-info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
            }

            .a4-container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 20mm;
                box-shadow: none;
            }

            .footer-section {
                position: absolute;
                bottom: 20mm;
                left: 20mm;
                right: 20mm;
            }
        }

        @media screen {
            .a4-container {
                margin: 20px auto;
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
        <p>CR: %registration_number%</p>
    </div>

    <div class="receipt-title">
        <h1>Receipt Voucher</h1>
    </div>

    <div class="doc-date-section">
        <div>Doc. #: <strong>%receipt_code%</strong></div>
        <div>Date: <strong>%payment_date%</strong></div>
        <div>VAT Value: <strong>0.00</strong></div>
    </div>

    <div class="details">
        <div>Paid To: <strong>%account_to%</strong></div>
        <div>Account #: <strong>%account_ledger_to%</strong></div>
        <div>The Amount of: <strong>%receipt_amount% %receipt_currency%</strong> (%receipt_amount_letters% %receipt_currency% ONLY)</div>
        <div>Being For: <strong dir="rtl">%receipt_description%</strong></div>
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
            <td>%receipt_amount%</td>
            <td>%receipt_currency%</td>
            <td>%paied_account%</td>
            <td>%payment_date%</td>
        </tr>
        </tbody>
    </table>

    <div class="footer-section">
        <div class="signature"> <span style="padding-right: 50px">Prepared By:</span> <span style="padding-right: 50px">Received By:</span> <span style="padding-right: 50px">Management:</span> <span style="padding-right: 50px">Accounting:</span> </div>
        <br/>
        <br/>
        <div class="print-info">
            <span><strong>Created By:</strong> %CREATED_BY%</span> |
            <span><strong>Printed By:</strong> %PRINTED_BY%</span> |
            <span><strong>Print Date:</strong> %PRINT_DATE%</span>
        </div>
    </div>
</div>
</body>
</html>
