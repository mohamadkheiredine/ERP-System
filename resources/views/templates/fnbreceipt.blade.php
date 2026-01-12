<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .receipt {}

        .header,
        .footer {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {}

        .items {}

        .items table {
            width: 100%;
            border-collapse: collapse;
        }

        .items table,
        .items th,
        .items td {}

        .items th,
        .items td {
            padding: 8px;
            text-align: left;
        }

        .totals {
            margin: 20px 0;
        }

        .totals table {
            width: 100%;
        }

        .totals th,
        .totals td {
            padding: 8px;
            text-align: left;
        }

        .totals th {
            text-align: left;
        }

        .footer p {
            margin: 10px 0 0;
        }
    </style>
</head>

@php
    $fmt = fn ($v) => number_format((float)($v ?? 0), 2);

    $currencyCode = $currency->cc_currency_code ?? '';

    $lineTotal = function ($item) {
        if (isset($item['total'])) {
            return (float) $item['total'];
        }

        if (isset($item['unit_price'], $item['quantity'])) {
            return (float)$item['unit_price'] * (float)$item['quantity'];
        }

        return 0;
    };
@endphp



<body>
    <div class="receipt">
        <div style="width:100%;text-align:center">
            <p style="width:100%;font-weight:bold" align="center">{{ $customer_info->cd_company_name ?? 'Walk-in Customer' }}</p>
            <p style="width:100%;" align="center">{{ $creation_date }}&nbsp;&nbsp;</p>
            <p style="width:100%;" align="center">FACTURE N:<b>{{ $fo_order_code }}</b></p>
            <p style="width:100%;font-weight:bold" align="center">{{ $company_info->cd_company_phone ?? '' }}</p>
            <p style="width:100%;font-weight:bold" align="center">code marchand: 590953</p>
        </div>
        @if(isset($delivery_id) && $delivery_id != 0)
            <div id="mid" style="text-align: center; margin-top: 10px;">
                <div class="info" style="display: inline-block; text-align: left;">
                    <h2 style="margin-bottom: 8px;">Contact Info</h2>
                    <p style="line-height: 1.6; font-size: 14px;">
                        <strong>Name:</strong> {{ $customer_info->ic_customer_name }} <br>
                        <strong>Address:</strong> {{ $customer_info->ic_customer_address }} <br>
                        <strong>Phone:</strong> {{ $customer_info->ic_customer_phone }} <br>
                    </p>
                </div>
            </div>
        @endif
        <div class="items">
            <table border='0' style='width:100%;'>
                <thead>
                </thead>
                <tbody>
                    @foreach($lst_order_items as $order_item)
                        <tr>
                            <td>
                                {{ $order_item['item_name'] }} x {{ $order_item['quantity'] }}

                                @if(!empty($order_item['modifiers']))
                                    <br>
                                    <small>
                                        @foreach($order_item['modifiers'] as $m)
                                            - {{ $m['name'] }} ({{ $fmt($m['price']) }})<br>
                                        @endforeach
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $fmt($lineTotal($order_item)) }} {{ $currencyCode }}
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
        <div class="items">
            <table style="width:100%">
                <tr>
                    <th align="left">Subtotal:</th>
                    <td align="left">
                        {{ $fmt($sub_total) }} <b>{{ $currencyCode }}</b>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th align="left">Discount :</th>
                    <td align="left">{{ $discount }}</td>
                    <td></td>
                </tr>
                <tr>
                    <th align="left">Total:</th>
                    <td align="left">
                        {{ $fmt($cost_total) }} <b>{{ $currencyCode }}</b>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>
                Merci pour votre visite<br />
                Au cas de probleme contactez-nous immediatement
            </p>
            {{-- <p>{{$company_info->cd_company_name}}</p> --}}
        </div>
    </div>
</body>

</html>
