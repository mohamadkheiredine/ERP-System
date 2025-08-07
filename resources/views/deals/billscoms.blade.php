<?php
/***********************************************************
 * billscoms.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/31/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

<div class="table-responsive" id="LstPaymentsMain">
<table class="table table-striped">
    <thead>
    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
        <th title="Bill#" style="width:60%"> Bill# </th>
        <th title="Value Date"> Com Value </th>
    </tr>
    </thead>
    <tbody  id="LstMiniBills" >
    @foreach($payments_array  as $index => $payment_info)
        <tr  class="odd gradeX">
            <td>{{ $payment_info['bill_nbr'] }}</td>
            <td><input type="text" name="bill_sales_commission[]"  class="form-control" value="{{ $payment_info['bill_sales_commission'] }}" /></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
