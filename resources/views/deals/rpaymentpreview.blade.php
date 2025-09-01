<?php
/***********************************************************
 * rpaymentpreview.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/20/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($payments_array  as $index => $payment_info)
    <tr  class="odd gradeX">
        <td>{{ $payment_info['bill_nbr'] }}</td>
        <td>{{ $payment_info['value_date'] }}</td>
        <td>{{ $payment_info['bill_status'] }}</td>
        <td>{{ $payment_info['bill_amount'] }}</td>
    </tr>
@endforeach

