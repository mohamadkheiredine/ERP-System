<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h3>Waste Report</h3>
<p>Printed by: %PRINTED_BY%</p>
<p>Print date: %PRINT_DATE%</p>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Product Name</th>
            <th>Warehouse Name</th>
            <th>Quantity</th>
            <th>Unit</th>
        </tr>
    </thead>
    <tbody>
        %WASTE_ROWS%
    </tbody>
</table>

</body>
</html>
