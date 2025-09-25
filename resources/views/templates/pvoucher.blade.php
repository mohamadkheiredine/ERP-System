<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
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
        .voucher-container {
            background-color: white;
            border: 2px solid black;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            min-height: 500px;
        }
        .voucher-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .voucher-title {
            text-align: center;
            flex-grow: 1;
        }
        .doc-date {
            text-align: right;
            min-width: 200px;
        }
        .doc-date div {
            margin-bottom: 5px;
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
            float: left;
            width: 25%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>%company_name%</h1>
        <p>%company_address%</p>
        <p>Phone: %company_phone%</p>
    </div>

    <div class="voucher-container">
        <div class="voucher-header">
            <div></div>
            <div class="voucher-title">
                <h1><u>Payment Voucher</u></h1>
            </div>
        </div>

        <div class="details">
            <div class="doc-date">
                <div>Doc. #: <strong>%voucher_code%</strong></div>
                <div>Date: <strong>%payment_date%</strong></div>
                <div>VAT Value: <strong>0.00</strong></div>
            </div>
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

        <div style="width:100%;height:100px">&nbsp;</div>

        <div class="signature">
            <div>Prepared By</div>
            <div>Received By</div>
            <div>Management</div>
            <div>Accounting</div>
        </div>
        <div style="width:100%;height:50px">&nbsp;</div>
    </div>
</div>
</body>
</html>
