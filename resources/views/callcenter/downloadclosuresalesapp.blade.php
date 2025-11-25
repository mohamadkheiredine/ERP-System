<?php

/***********************************************************
downloadclosuresalesapp
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 10, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
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

        .print-footer {
            position: fixed;
            bottom: 10mm;       /* distance from bottom of page */
            left: 0;
            width: 100%;
            font-size: 12px;
            color: #444;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        /* Print settings for A4 */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            body {
                background: white !important;
                padding: 0;
            }

            .print-footer {
                position: fixed;
                bottom: 10mm;
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
                <div class="report-title">Closure Sales App Report</div>
            </div>
        </div>
        <div class="meta">
            <div><strong>Report Date:</strong> <span>{{ date('Y-m-d') }}</span></div>
        </div>
    </header>

    @if(count($lst_closing_res) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                <tr>
                    @foreach(array_keys((array)$lst_closing_res[0]) as $column)
                        <th>{{ ucwords(str_replace('_', ' ', $column)) }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($lst_closing_res as $row)
                    <tr>
                        @foreach((array)$row as $value)
                            <td>
                                @if(is_numeric($value))
                                    {{ $value }}
                                @else
                                    {{ $value }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info mb-0">No data found for the selected filters.</div>
    @endif



    <div class="footer-notes" style="margin-top:25px; font-size:12px; color:#444; display:flex; justify-content:space-between;">
        <span><strong>Printed By:</strong> {{ session('user_fullname') }}</span> |
        <span><strong>Print Date:</strong> {{ date('Y-m-d H:i:s') }}</span>
    </div>
</div>
</body>
</html>

