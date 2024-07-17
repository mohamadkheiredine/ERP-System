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
        .receipt {

        }
        .header, .footer {
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
          
        }
        .items {
           
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items table, .items th, .items td {
     
        }
        .items th, .items td {
            padding: 8px;
            text-align: left;
        }
        .totals {
            margin: 20px 0;
        }
        .totals table {
            width: 100%;
        }
        .totals th, .totals td {
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
<body>
    <div class="receipt">
        <div style="width:100%;text-align:center">
            <p  style="width:100%;font-weight:bold" align="center">{{$company_info->cd_company_name}}</p>
            <p style="width:100%;" align="center">{{ $creation_date }}&nbsp;&nbsp;{{ $creation_time }}</p>
            <p style="width:100%;" align="center">FACTURE N:<b>{{ $so_order_code }}</b></p>
			<p  style="width:100%;font-weight:bold" align="center">{{$company_info->cd_company_phone}}</p>
			<p  style="width:100%;font-weight:bold" align="center">code marchand: 590953</p>
        </div>
            @if(isset($delivery_id) && $delivery_id != 0)
            <div id="mid">
              <div class="info">
                <h2>Contact Info</h2>
                <p> 
                    Name   : {{$customer_info->ic_customer_name}}</br>
                    Address : {{$customer_info->ic_customer_address}}</br>
                    Phone   : {{$customer_info->ic_customer_phone}}</br>
                </p>
              </div>
            </div>
            @endif
        <div class="items">
            <table border='0' style='width:100%;'>
                <thead> 
                </thead>
                <tbody>
                    @foreach($lst_order_items as $index => $order_item)
                    <tr>
                        <td>{{ $order_item->fk_product_id > 0  ?  $order_item->Products->p_product_name : $order_item->so_unit_label }}</td>
                        <td>{{ $order_item->so_product_quantity }}</td>
                        <td>{{ number_format($order_item->so_product_price * $order_item->so_product_quantity,2) }}&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b>&nbsp;</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="items">
            <table style="width:100%">
                <tr>
                    <th align="left">Subtotal:</th>
                    <td align="left">{{ number_format($pos_sub_total,2) }}&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b></td>
                    <td></td>
                </tr>
                <tr>
                    <th align="left">Discount :</th>
                    <td align="left">{{ $pos_discount }}</td>
                    <td></td>
                </tr>
                <tr>
                    <th align="left">Total:</th>
                    <td align="left">{{ number_format(( $cost_total),2) }}</b>&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b>&nbsp;</td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>
			Merci pour votre visite<br/>
			Au cas de probleme contactez-nous immediatement
			</p>
            <p>{{$company_info->cd_company_name}}</p>
        </div>
    </div>
</body>
</html>
