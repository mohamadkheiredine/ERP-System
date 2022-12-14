<?php
/***********************************************************
receiptpayment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 17, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<table class="table" style="width:100%" >
    <thead>
        <tr>
            <th>Payment</th>
            <th class="text-center">Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:90%"><h4><em>{{ $receipt_info->br_receipt_label }}</em></h4></td>
            <td style="width:10%"> {{ $receipt_info->br_payment_value }}&nbsp;&nbsp;{{ $currencies_array[$receipt_info->br_receipt_currency]['cc_currency_code'] }} </td> 
        </tr>
        <tr>
            <td class="text-right"><h4><strong>Total: </strong></h4></td>
            <td class="text-center text-danger"><h4><strong> {{ $receipt_info->br_payment_value }}&nbsp;&nbsp;{{ $currencies_array[$receipt_info->br_receipt_currency]['cc_currency_code'] }} </strong></h4></td>
        </tr>
    </tbody>
</table>