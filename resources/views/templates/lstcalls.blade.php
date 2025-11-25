<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Calls / Maintenance Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #c3cfe2 ;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }

        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .report-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: #764ba2;
        }

        .report-header {
            text-align: center;
            padding: 40px 30px 30px;
            background: #764ba2;
            color: white;
            position: relative;
        }

        .report-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .report-title {
            font-size: 2.5rem;
            font-weight: 300;
            letter-spacing: 2px;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .report-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 10px;
            font-weight: 400;
        }

        .report-body {
            padding: 40px;
        }

        .tech-section {
            margin-bottom: 40px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            background: white;
            border: 1px solid #e8ecf4;
        }

        .tech-header {
            background: #00f2fe;
            color: white;
            padding: 20px 25px;
            font-size: 1.3rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .tech-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
        }

        #tablPendingCalls {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            background: white;
        }

        #tablPendingCalls thead {
            background: #e9ecef;
        }

        #tablPendingCalls th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            position: relative;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #tablPendingCalls th:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 25%;
            bottom: 25%;
            width: 1px;
            background: #dee2e6;
        }

        #tablPendingCalls td {
            padding: 5px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            transition: background-color 0.2s ease;
        }

        #tablPendingCalls tbody tr {
            transition: all 0.2s ease;
        }

        #tablPendingCalls tbody tr:hover {
            background: #f0f4ff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        #tablPendingCalls tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }

        #tablPendingCalls tbody tr:nth-child(even):hover {
            background: #f0f4ff;
        }

        .call-id {
            font-weight: 700;
            color: #667eea;
            background: #f0f3ff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            min-width: 40px;
            text-align: center;
        }

        .client-info {
            font-weight: 600;
            color: #2d3748;
        }

        .account-code {
            color: #667eea;
            font-weight: 600;
        }

        .date-cell {
            color: #4a5568;
            font-weight: 500;
        }

        .address-cell {
            color: #718096;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .phone-cell {
            font-weight: 600;
            color: #2b6cb0;
            font-family: 'Courier New', monospace;
        }

        .machine-id {
            background: #e2e8f0;
            color: #4a5568;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .report-title {
                font-size: 2rem;
            }

            .report-body {
                padding: 20px;
            }

            #tablPendingCalls {
                font-size: 0.85rem;
            }

            #tablPendingCalls th,
            #tablPendingCalls td {
                padding: 12px 8px;
            }

            .address-cell {
                max-width: 120px;
            }
        }

        /* Print styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .report-container {
                box-shadow: none;
                border-radius: 0;
            }

            .tech-section {
                break-inside: avoid;
                box-shadow: none;
            }
        }

        /* Loading animation for dynamic content */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tech-section {
            animation: fadeIn 0.6s ease-out;
        }

        /* Footer that stays at bottom of A4 landscape */
        .print-footer {
            position: fixed;
            bottom: 10px;
            left: 30%;
            width: 100%;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            color: #333;
        }

        /* For print - landscape + A4 */
        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm;
            }

            body {
                padding: 0;
                background: white !important;
            }

            .print-footer {
                position: fixed;
                bottom: 10mm;
            }

            .report-container {
                border-radius: 0 !important;
                box-shadow: none !important;
            }
        }

    </style>
</head>
<body>
<div class="report-container">
    <div class="report-body">
            @foreach($calls_array as $index => $tech_info)
            <div class="tech-section">
                <div class="tech-header" style="text-align: center">
{{ $tech_info['user_info']['name'] }}
            </div>
            <table id="tablPendingCalls">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Item</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Maintenance Type</th>
                    </tr>
                </thead>
                <tbody class="LstInboundCalls">
@foreach($tech_info['call_info'] as $index => $call_info)
                <tr>
                                    <td class="date-cell">{{$call_info['ic_call_date']}}</td>
                                    <td class="client-info"><span class="account-code">#{{$call_info['ca_account_code']}}</span> {{$call_info['ca_account_name']}}</td>
                                    <td><span class="machine-id">{{$call_info['ic_product_machine_id']}}</span></td>
                                    <td class="address-cell"><span>{{ $call_info['ca_billing_address'] }}</span></td>
                                    <td class="phone-cell">{{$call_info['ca_account_mobile']}}</td>
                    <td>
                        {{ $call_info['ic_maintenance_type'] }}
                    </td>

                                </tr>
    <tr>
        <td colspan="5">
            @if(!empty($call_info['cr_result_title']))
                <span style="color:#667eea;font-weight:600;font-size:16px">Result: {{ $call_info['cr_result_title'] }}</span>
            @endif
            <span style="color:#667eea;font-weight:600;font-size:16px">date: {{ $call_info['ic_callback_date'] }}</span>
            @if(!empty($call_info['ic_result_notes']))
                <span style="color:#718096;font-size:16px;font-weight:600;">Note: {{ strip_tags($call_info['ic_result_notes']) }}</span>
            @endif
        </td>
    </tr>
                            @endforeach
            </tbody>
        </table>
    </div>
@endforeach
    </div>


</div>

<div class="print-footer">
    <span><strong>Printed By:</strong> {{ session('user_fullname') }}</span>
    <span><strong>Print Date:</strong>{{ date('d-m-Y H:i:s') }}</span>
</div>
</body>
</html>
