<?php
/***********************************************************
 * listcyclestock.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 1/25/2026
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2026
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($lst_cycle_stock as $index => $stock_info)
    <tr   class="odd gradeX" data-fs_id="{{ $stock_info->fs_id }}">
        <td>{{ $expense_info->Warehouse->w_warehouse_name }}</td>
        <td>{{ $expense_info->Product->p_barcode }}</td>
        <td>{{ $expense_info->Product->p_product_name }}</td>
        <td>{{ $expense_info->fs_quantity }}</td>
        <td style="width:2px;"> <a href="#"  data-fs_id="{{ $stock_info->fs_id }}"  id="DELETE_STOCK_{{ $stock_info->fs_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
    </tr>
@endforeach
