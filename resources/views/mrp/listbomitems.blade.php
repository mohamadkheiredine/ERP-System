<?php
/***********************************************************
listbomitems.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 5, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>



@foreach($lst_bom_items  as $index => $item_info)
 <tr>
	<th scope="row">{{ $item_info->products->p_id }}</th>
	<td>{{ $item_info->products->p_product_name }}</td>
	<td>{{ $item_info->bi_item_quanity }}</td>
	<td>{{ $item_info->bi_total_price }}&nbsp;&nbsp;<b>{{ $item_info->currency->cc_currency_code }}</b></td>
	<td><a href="#" data-bi_id="{{ $item_info->bi_id }}"  id="DELETE_ITEM_{{ $item_info->bi_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
</tr>
@endforeach
