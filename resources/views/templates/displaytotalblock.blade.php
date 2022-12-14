<?php
/***********************************************************
displaytotalblock.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/
?>

<table cellspacing="0" cellpadding="0" border="0" style="width:150px">
	@foreach( $total_stock_amount as $currency_code => $amount_number )
	<tr>
		<td style="width:90%">
			{{ number_format($amount_number) }}
		</td>
		<td style="width:10%">
			<b>{{ $currency_code }}</b>
		</td>
	</tr>
	@endforeach
</table>