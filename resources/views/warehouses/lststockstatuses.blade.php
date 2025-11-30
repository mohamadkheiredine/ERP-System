<?php
/***********************************************************
 * lststockstatuses.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 11/30/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

<?php  foreach ( $lst_stock_status as $key => $stock_info ) { ?>
<tr>
    <td>{{ $stock_info->warehouse_name }}</td>
    <td>{{ $stock_info->p_product_ref }}</td>
    <td>{{ $stock_info->p_product_name }}</td>
    <td>{{ $stock_info->total_stock }}</td>
    <td>{{ $stock_info->stock_status }}</td>
</tr>
<?php } ?>
