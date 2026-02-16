<?php
/***********************************************************
 * lststockavailability.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/1/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

<?php  foreach ( $lst_stock_availability as $key => $stock_info ) { ?>
<tr>
    <td>{{ $stock_info->p_id }}</td>
    <td>{{ $stock_info->w_warehouse_name }}</td>
    <td>{{ $stock_info->p_barcode }}</td>
    <td>{{ $stock_info->p_product_name }}</td>
    <td>{{ $stock_info->is_price_item }}</td>
    <td>{{ $stock_info->is_price_stock }}</td>
    <td>{{ $stock_info->total_quantity }}</td>
</tr>
<?php } ?>
