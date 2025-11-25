<?php
/***********************************************************
 * printstockavailability.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/2/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Stock Availability Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root{
            --text:#111;
            --muted:#666;
            --border:#ddd;
            --accent:#0d6efd; /* tweak if you like */
        }

        /* Layout */
        html, body { background:#fff; color:var(--text); }
        body{
            font: 12px/1.45 "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin:0; padding:0;
        }
        .page{
            max-width: 210mm; /* A4 width */
            margin: 0 auto;
            padding: 16mm 14mm; /* room for printer margins */
            box-sizing: border-box;
        }

        /* Header */
        header{
            display:flex; align-items:flex-start; justify-content:space-between;
            margin-bottom: 16px; border-bottom:1px solid var(--border); padding-bottom:10px;
        }
        .brand{
            display:flex; gap:12px; align-items:center;
        }
        .logo{
            width:36px; height:36px; border:1px solid var(--border); border-radius:6px;
            display:inline-block; background:#f7f7f7;
        }
        .brand h1{
            font-size:16px; margin:0; letter-spacing:0.2px;
        }
        .meta{
            text-align:right; font-size:11px; color:var(--muted);
        }
        .report-title{
            font-size:18px; margin:6px 0 0; font-weight:700; color:var(--accent);
        }

        /* Table */
        table{
            width:100%; border-collapse:collapse; margin-top:14px;
            table-layout:fixed;
        }
        thead th{
            font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.04em;
            color:#222; background:#f3f5f8; border:1px solid var(--border);
            padding:8px 10px;
        }
        tbody td{
            border:1px solid var(--border); padding:8px 10px; vertical-align:top;
            word-wrap:break-word;
        }
        tbody tr:nth-child(even) td{ background:#fafbfc; }
        .col-id{ width:18%; }
        .col-name{ width:42%; }
        .col-warehouse{ width:22%; }
        .col-total{ width:18%; text-align:right; }

        /* Footnotes / signature */
        .footer-notes{
            margin-top:16px; display:flex; gap:24px; font-size:11px; color:var(--muted);
        }
        .sign-box{
            border-top:1px solid var(--border); padding-top:8px; min-width:160px;
        }

        /* Print rules */
        @page { size: A4 portrait; margin: 12mm; }
        @media print {
            html, body { background:#fff; }
            .page { padding:0; margin:0; }
            header { border-bottom:1px solid #ccc; }
            thead { display: table-header-group; } /* repeat header on each page */
            tfoot { display: table-footer-group; }
            tr { page-break-inside: avoid; }
            .footer-notes { page-break-inside: avoid; }
            a { color: inherit; text-decoration: none; }
        }

        .logo img{
            width: 96px;
            height: auto;
        }


        /* Fixed footer for printing at bottom of A4 */
        .print-footer {
            position: fixed;
            bottom: 10mm; /* distance from bottom of page */
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #444;
            display: flex;
            justify-content: center;
            gap: 40px;
        }

        /* Print mode settings */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            .print-footer {
                position: fixed;
                bottom: 10mm;
                left: 0;
                width: 100%;
            }
        }

    </style>
</head>
<body>
<div class="page">
    <header>
        <div class="brand">
            <!-- Replace with your logo image if desired -->
            <div class="logo">
                <img src="{{ session('company_logo') }}" />
            </div>
            <div>
                <h1></h1>
                <div class="report-title">Stock Availability Report</div>
            </div>
        </div>
        <div class="meta">
            <div><strong>Report Date:</strong> <span>{{ date('Y-m-d') }}</span></div>
        </div>
    </header>

    <table aria-label="Stock Availability">
        <thead>
        <tr>
            <th class="col-id">ID</th>
            <th class="col-name">Product Code</th>
            <th class="col-name">Product Name</th>
            <th class="col-warehouse">Warehouse</th>
            <th class="col-total">Stock Total</th>
        </tr>
        </thead>
        <tbody>
        <!-- Duplicate <tr> for each product row -->
        @foreach($lst_stock_availability as $index => $stock_info)
            <tr>
                <td class="col-id">{{ $stock_info->p_id }}</td>
                <td class="col-name">{{ $stock_info->p_barcode }} </td>
                <td class="col-name">{{ $stock_info->p_product_name }}</td>
                <td class="col-warehouse">{{ $stock_info->w_warehouse_name }}</td>
                <td class="col-total">{{ $stock_info->total_quantity }}</td>
            </tr>

        @endforeach
        <!-- End sample rows -->
        </tbody>
    </table>

</div>

<div class="print-footer">
    <span><strong>Printed By:</strong> {{ session('user_fullname') }}</span>
    <span><strong>Print Date:</strong> {{ date('Y-m-d H:i:s') }}</span>
</div>
</body>
</html>

