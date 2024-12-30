<?php
/***********************************************************
displaylisttransfer.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 24, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

@foreach($productStockMovements as $index => $sm_info)
<tr  class="odd gradeX" data-sm_id="{{ $sm_info->sm_id }}">
	<td><input type="checkbox" name="ck_sm_{{ $sm_info->is_id }}" id="CK_SM_{{ $sm_info->sm_id }}" class="checkboxes" value="{{ $sm_info->sm_id }}" /></td>
    <td>{{ $sm_info->sm_id }}</td>
    <td>{{ $sm_info->sm_date_movement }}</td>
    <td>{{ $sm_info->CreatedBy->u_fullname }}</td>
    <td>{{ $warehouses_array[$sm_info->fk_warehouse_from]['w_warehouse_name'] }}</td>
    <td>{{ $warehouses_array[$sm_info->fk_warehouse_to]['w_warehouse_name'] }}</td>
    <td>{{ $sm_info->sm_stock_total_price }}</td>
    <td>{{ $sm_info->sm_stock_quantity }}</td>
</tr>
@endforeach