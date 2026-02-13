<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recipe Print</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        header {
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            border-top: 1px solid #999;
            font-size: 10px;
            text-align: center;
            padding-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
        }

        .logo {
            height: 50px;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <table width="100%">
        <tr>
            <td>
                @if(!empty($logoSrc))
                    <img src="{{ $logoSrc }}" class="logo" alt="Company Logo">
                @endif
            </td>
            <td align="right">
                <strong>Recipe:</strong> {{ $item->mi_item_name }}<br>
                <strong>Date:</strong> {{ $printedAt->format('Y-m-d H:i') }}
            </td>
        </tr>
    </table>
</header>

<h3>Ingredients</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Ingredient</th>
            <th>Qty</th>
            <th>UoM</th>
            <th>Waste %</th>
            <th>Unit Cost</th>
            <th>Line Cost</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ingredients as $index => $ing)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ing->in_ingredient_name ?: (optional($ing->Product)->p_product_name ?? '-') }}</td>
                <td>{{ $ing->in_stock_quantity }}</td>
                <td>{{ optional($ing->Unit)->su_unit_label ?? '-' }}</td>
                <td>{{ $ing->in_waste_percent }}%</td>
                <td class="text-right">
                    {{ number_format($ing->in_cost_per_unit, 2) }} {{ $currency }}
                </td>
                <td class="text-right">
                    {{ number_format($ing->in_line_cost, 2) }} {{ $currency }}
                </td>
                <td>{{ $ing->in_notes }}</td>
            </tr>
        @endforeach

        {{-- TOTAL ROW --}}
        <tr>
            <td colspan="6" class="text-right fw-bold">Total</td>
            <td class="text-right fw-bold">
                {{ number_format($totalCost, 2) }} {{ $currency }}
            </td>
            <td></td>
        </tr>
    </tbody>
</table>

@if(!empty($item->mi_item_description))
<h3 style="margin-top: 20px;">Preparation Steps</h3>
<div style="border: 1px solid #ccc; padding: 10px; white-space: pre-wrap;">{{ $item->mi_item_description }}</div>
@endif

<footer>
    Created by: {{ $printedBy }} &nbsp;|&nbsp;
    Date: {{ $printedAt->format('Y-m-d H:i') }}
</footer>

</body>
</html>
