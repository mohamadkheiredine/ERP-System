<?php
/***********************************************************
 * returnproductspreview.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 2/9/2026
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2026
 *
 * Page Description :
 ***********************************************************/

?>
<input type="hidden" name="bi_id" value="{{ $bi_id }}" />
<table class="table table-striped gy-7 gs-7">
    <thead>
    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
        <th title="Product"> Product </th>
        <th title="Quantity"> Quantity </th>
        <th title="Return Quantity"> Return Quantity </th>
        <th title="Serial Number"> Serial Number </th>
        <th title="Warehouse"> Warehouse </th>
        <th title="Stock Type"> Stock Type </th>
    </tr>
    </thead>
    <tbody id="LstReturnProducts">
    @foreach( $lst_invoice_items as $index => $item_info )
        <tr>
            <td>
                <b>{{ $item_info->Product->p_product_name }}</b>
                <input type="hidden" name="sp_product_id[]" value="{{ $item_info->ii_item_id }}" />
            </td>
            <td>
                {{ $item_info->ii_item_qyt }}
                <input type="hidden" name="sp_quantity[]" value="{{ $item_info->ii_item_qyt }}" />
            </td>
            <td>
                <input type="text" class="form-control" name="sp_return_quantity[]" value="{{ $item_info->ii_item_qyt }}" />
            </td>
            <td>
                <input type="text" class="form-control" name="sp_serial_number[]" value="{{ $item_info->ii_product_serial_number }}" />
            </td>
            <td>
                <select   data-control="select2" data-placeholder="Select a warehouse" class="form-select" name="sp_warehouse_id[]" data-actions-box="true">
                    <option value="">-- Select Warehouse --</option>
                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                        <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select   data-control="select2" data-placeholder="Select a stock type" class="form-select" name="sp_stock_type[]" data-actions-box="true">
                    <option value="">-- Select Stock Type --</option>
                    <option value="3">Returned</option>
                    <option value="2">defective</option>
                    <option value="4">Keep it</option>
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
