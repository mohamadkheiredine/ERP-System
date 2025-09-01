<?php
/***********************************************************
paymentpreview
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 8, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@foreach($payments_array  as $index => $payment_info)
<tr  class="odd gradeX">
   <td>{{ $payment_info['bill_nbr'] }}</td>
   <td>{{ $payment_info['value_date'] }}</td>
   <td>{{ $payment_info['bill_status'] }}</td>
   <td><input type="text" name="bill_amount[]" value="{{ $payment_info['bill_amount'] }}" class="form-control" /></td>
</tr>
@endforeach
