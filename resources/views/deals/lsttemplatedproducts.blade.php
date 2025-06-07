<?php
/***********************************************************
lsttemplatedproducts
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 10, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@for ($i =1; $i <= ( count($lst_invoice_payments) - 1); $i++)
<tr  class="odd gradeX">
   <td>{{ $lst_invoice_payments[$i]->ip_billing_nbr }}</td>
   <td>{{ $lst_invoice_payments[$i]->ip_billing_date }}</td>
   <td>{{ $lst_invoice_payments[$i]->ip_payment_amount }}</td>
   <td>{{  $currency_name }}</td>
</tr>
@endfor
