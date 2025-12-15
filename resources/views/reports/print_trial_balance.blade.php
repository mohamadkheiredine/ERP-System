<?php
/***********************************************************
 * print_trial_balance.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 12/12/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trial Balance</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .report-container {
            width: 100%;
            padding: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            background: #f2f2f2;
            font-weight: bold;
        }

        tbody td {
            border: 1px solid #000;
            padding: 5px;
        }

        tfoot td {
            border: 1px solid #000;
            padding: 6px;
            font-weight: bold;
            background: #eaeaea;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .account-name {
            padding-left: 10px;
        }

        .footer {
            margin-top: 15px;
            font-size: 11px;
            text-align: right;
        }

        @page {
            margin: 20px;
        }
    </style>
</head>

<body>

<div class="report-container">

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <h2>TRIAL BALANCE</h2>
        <p>
            Period:
            <strong>{{ $date_from }}</strong>
            to
            <strong>{{ $date_to }}</strong>
        </p>
    </div>

    {{-- ================= TABLE ================= --}}
    <table>
        <thead>
        <tr>
            <th width="10%">Account Code</th>
            <th width="30%">Account Name</th>
            <th width="15%">Opening Balance</th>
            <th width="15%">Debit</th>
            <th width="15%">Credit</th>
            <th width="15%">Closing Balance</th>
        </tr>
        </thead>

        <tbody>
        @php
            $total_opening = 0;
            $total_debit   = 0;
            $total_credit  = 0;
            $total_closing = 0;
        @endphp

        @foreach($trial_balance as $row)
            @php
                $closing = $row->opening_balance + ($row->period_debits - $row->period_credits);

                $total_opening += $row->opening_balance;
                $total_debit   += $row->period_debits;
                $total_credit  += $row->period_credits;
                $total_closing += $closing;
            @endphp

            <tr>
                <td class="text-center">{{ $row->account_code }}</td>
                <td class="account-name">{{ $row->account_name }}</td>
                <td class="text-right">{{ number_format($row->opening_balance, 2) }}</td>
                <td class="text-right">{{ number_format($row->period_debits, 2) }}</td>
                <td class="text-right">{{ number_format($row->period_credits, 2) }}</td>
                <td class="text-right">{{ number_format($closing, 2) }}</td>
            </tr>
        @endforeach
        </tbody>

        {{-- ================= TOTALS ================= --}}
        <tfoot>
        <tr>
            <td colspan="2" class="text-center">TOTAL</td>
            <td class="text-right">{{ number_format($total_opening, 2) }}</td>
            <td class="text-right">{{ number_format($total_debit, 2) }}</td>
            <td class="text-right">{{ number_format($total_credit, 2) }}</td>
            <td class="text-right">{{ number_format($total_closing, 2) }}</td>
        </tr>
        </tfoot>
    </table>

    {{-- ================= FOOTER ================= --}}
    <div class="footer">
        Generated on {{ date('Y-m-d H:i') }}
    </div>

</div>

</body>
</html>

