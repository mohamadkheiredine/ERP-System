<?php
/***********************************************************
 * lstpackingprices.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 5/31/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>
@foreach($items as $index => $packing_info)
    <tr class="total-row">
        <td>{{ $packing_info->Category->pc_category  }}</td>
        <td>{{ $index  }}</td>
        <td>{{  $packing_info->so_package_weight }}</td>
        <td>{{ $packing_info->so_package_cost  }}</td>
        <td>{{ $packing_info->so_package_price  }}</td>
    </tr>
@endforeach
