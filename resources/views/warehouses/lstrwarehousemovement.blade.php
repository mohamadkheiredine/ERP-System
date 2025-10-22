<?php
/***********************************************************
 * lstrwarehousemovement.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 10/5/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


<?php  foreach ( $lst_warehouse_movements as $key => $movement_info ) { ?>
<tr>
    <td>{{ $movement_info->wm_action_type }}</td>
    <td>{{ $movement_info->wm_action_description }}</td>
    <td>{{ $movement_info->Warehouse->w_warehouse_name }}</td>
    <td>{{ $movement_info->Product->p_barcode }}</td>
    <td>{{ $movement_info->Product->p_product_name }}</td>
    <td>{{ $movement_info->wm_quantity }}</td>
</tr>
<?php } ?>

