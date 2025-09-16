<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Voucher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: left;
            margin-bottom: 30px;
            background-color: white;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 2px;
        }
        .receipt-container {
            background-color: white;
            border: 2px solid black;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 500px;
            padding: 20px;
        }
        .receipt-title {
            width: 100%;
            text-align: center;
            margin: 0 0 20px 0;
        }
        .doc-date-section {
            float: right;
            text-align: right;
            margin-bottom: 20px;
        }
        .doc-date-section div {
            margin-bottom: 10px;
        }
        .details {
            margin-bottom: 30px;
            clear: both;
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
            margin-top: 50px;
            padding-top: 30px;
        }
        .signature div {
            text-align: center;
            float: left;
            width: 25%;
            min-height: 60px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>%company_name%</h1>
        <p>%company_address%</p>
        <p>Phone: %company_phone%</p>
        <p>CR: %registration_number%</p>
    </div>

    <div class="receipt-container">
        <div class="receipt-title">
            <h3>Receipt Voucher</h3>
        </div>

        <div class="details">
            <div class="doc-date-section">
                <div>Doc. #: <strong>%receipt_code%</strong></div>
                <div>Date: <strong>%payment_date%</strong></div>
                <div>VAT Value: <strong>0.00</strong></div>
            </div>
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

        <div class="signature">
            <div>Prepared By:</div>
            <div>Received By:</div>
            <div>Management:</div>
            <div>Accounting:</div>
        </div>
    </div>
</div>
</body>
</html>
