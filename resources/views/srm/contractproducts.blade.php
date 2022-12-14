<?php
/***********************************************************
contractproducts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 30, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
 
?>

<table class="m-datatable" id="html_table" width="100%">
		<thead>
			<tr> 
				<th title="Supplier Category"> Product Name </th>
				<th title="Supplier Name"> Product Quantity </th>
				<th title="Supplier Name"> Product Cost </th>
				<th style="width:4px !important;" nowrap title="#">Delete</th>
			</tr>
		</thead>
		<tbody>
		     @foreach( $contract_products as $key => $prod_info )
                <tr>
    				<td>{{ $products_array[ $prod_info->fk_product_id ]['sp_product_name']  }}</td>
    				<td>{{ $prod_info->sr_product_quantity }}</td>
                       <td style="width:2px;"><a  data-sp_id="{{  $prod_info->fk_product_id }}"  href="#"  id="DELETE_CNT_SUPP_{{  $prod_info->fk_product_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    			</tr>
		     @endforeach
			</tbody>
</table>