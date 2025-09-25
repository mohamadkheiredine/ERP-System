<?php
/***********************************************************
invoiceproducts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach( $items_array as $index => $item_info )
<tr>
        <td>{{ $item_info['p_product_ref'] }}</td>
        <td>{{ $item_info['label'] }} {{ isset($item_info['serialnumber']) ? $item_info['serialnumber'] : "" }}</td>
        <td>{{ $item_info['quantity'] }}</td>
        <td>{{ $item_info['cost'] }}&nbsp;&nbsp;{{ $item_info['currency'] }}</td>
        <td>0</td>
        <td>{{ $item_info['price'] }}&nbsp;&nbsp;{{ $item_info['currency'] }}</td>
</tr>
@endforeach
