<?php
/***********************************************************
 * voucherstockrecord.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/9/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

<tr data-p_id="{{ $product_info->p_id }}">
    <td>{{ $product_info->p_barcode }}</td>
    <td>{{ $product_info->p_product_name }}</td>
    <td>{{ $cp_quantity }}</td>
    <td>
        <a href="#"   id="DeleteProductCall" ><i class="fa-solid fa-trash"></i></a>
    </td>
</tr>
