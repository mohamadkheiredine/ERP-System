<?php
/***********************************************************
lstitems.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 9, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@foreach($lst_items as $index => $item_info)
<tr  class="odd gradeX" data-index="{{ is_array($item_info) ? $item_info['mp_product_id'] : $item_info->mp_product_id }}">
   <td>{{ is_array($item_info) ? $item_info['p_barcode'] : $item_info->p_barcode }}</td>
   <td>{{ is_array($item_info) ? $item_info['mp_product_name'] : $item_info->mp_product_name }}</td>
   <td>{{ is_array($item_info) ? $item_info['mp_movement_quantity'] : $item_info->mp_movement_quantity }}</td>
   <td>{{ is_array($item_info) ? $item_info['mp_item_notes'] : $item_info->mp_item_notes }}</td>
</tr>
@endforeach
