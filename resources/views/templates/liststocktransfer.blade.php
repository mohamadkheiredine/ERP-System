<?php

/***********************************************************
liststocktransfer
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/
?>

@foreach($lst_transfer_items as $index => $item_info)
<tr>
    <td>{{ $item_info->Product->p_product_ref }}</td>
    <td>{{ $item_info->Product->p_product_name }}</td>
    <td>{{ $item_info->Product->p_product_quantity }}</td>
    <td>{{ $item_info->mp_item_notes }}</td>
</tr>
@endforeach