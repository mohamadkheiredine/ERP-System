<?php
/***********************************************************
 * print_balance_sheet.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 12/13/2025
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
    <title>Balance Sheet</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        /* ================= HEADER ================= */
        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
        }

        /* ================= TABLE ================= */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            background-color: #f2f2f2;
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
            background-color: #e6e6e6;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* ================= FOOTER ================= */
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

<div class="container">

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <h2>Balance Sheet</h2>
        <p>
            As of <strong>31 December {{ $year }}</strong>
        </p>
    </div>

    {{-- ================= TABLE ================= --}}
    <table>
        <thead>
        <tr>
            <th width="10%">Account Code</th>
            <th width="35%">Account Name</th>
            <th width="18%">Opening Balance</th>
            <th width="18%">Year Movement</th>
            <th width="19%">Final Balance</th>
        </tr>
        </thead>

        <tbody>
        @php
            $total_opening  = 0;
            $total_movement = 0;
            $total_final    = 0;
        @endphp

        @foreach($balance_sheet as $row)
            @php
                $total_opening  += $row->opening_balance ?? 0;
                $total_movement += $row->yearly_movement ?? 0;
                $total_final    += $row->final_balance ?? 0;
            @endphp

            <tr>
                <td class="text-center">{{ $row->account_code }}</td>
                <td>{{ $row->account_name }}</td>
                <td class="text-right">
                    {{ number_format($row->opening_balance ?? 0, 2) }}
                </td>
                <td class="text-right">
                    {{ number_format($row->yearly_movement ?? 0, 2) }}
                </td>
                <td class="text-right">
                    {{ number_format($row->final_balance ?? 0, 2) }}
                </td>
            </tr>
        @endforeach
        </tbody>

        {{-- ================= TOTALS ================= --}}
        <tfoot>
        <tr>
            <td colspan="2" class="text-center">TOTAL</td>
            <td class="text-right">{{ number_format($total_opening, 2) }}</td>
            <td class="text-right">{{ number_format($total_movement, 2) }}</td>
            <td class="text-right">{{ number_format($total_final, 2) }}</td>
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
