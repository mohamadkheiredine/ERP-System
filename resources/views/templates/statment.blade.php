<?php
?>
<html>
<head>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
.Description h5{
	font-family: tahoma;
	font-size:24px;
	text-decoration:underline; 
}
.Description span{
	font-family: tahoma;
	font-size:14px;
}
.td{
	text-align: center;
	border-width: 1px;
}
.th{
	text-align: center;
	border-width: 1px;
}
</style>
</head>
<body>
<?php

$total_debit    = 0;
$total_credit   = 0;
$total_data_debit   = array();
$total_data_credit   = array();
$total_income   = array();
$total_balance  = array();

?>
<div style="width:100%;height: 200px;">
<br/>
</div>
<table cellspacing="0" cellpadding="2" border="1" style="width:100%;padding-left:10px;padding-right:10px;">
	<thead>
		<tr> 
			<th style="width:10%;text-align: center;border-width: 1px;">Account</th>
			<th style="width:10%;text-align: center;border-width: 1px;">code</th>
			<th style="width:10%;text-align: center;border-width: 1px;">date</th>
			<th style="width:40%;text-align: center;border-width: 1px;">Label</th>
			<th style="width:20%;text-align: center;border-width: 1px;">Debit</th>
			<th style="width:15%;text-align: center;border-width: 1px;">Credit</th>
			<th style="width:15%;text-align: center;border-width: 1px;">Balance</th>
		</tr>
	</thead>
	<tbody >
		@foreach($account_balance as $curremcy => $balance )
		@foreach($balance as $account_id => $lst_balance )
		@foreach($lst_balance as $index => $balance_info )
		<?php  
		$total_debit          = $total_debit + $balance_info['debit'];
		$total_credit         = $total_credit+ $balance_info['credit'];
		$balance              = $balance_info['balance'];
		$account_payable      = $balance_info['account_payable'];
		$account_receivable   = $balance_info['account_receivable'];
		$date_creation        = $balance_info['date_creation'];
		$code                 = $balance_info['code'];
		$mov_desc             = isset($balance_info['mov_desc']) ? $balance_info['mov_desc'] : "";
		
		if(!isset($total_data_debit[ $balance_info['currency'] ] ))
		{
		    $total_data_debit[ $balance_info['currency'] ] = $balance_info['debit'];
		}
		else
		{
		    $total_data_debit[ $balance_info['currency'] ] += $balance_info['debit'];
		}
		
		if(!isset($total_data_credit[ $balance_info['currency'] ] ))
		{
		    $total_data_credit[ $balance_info['currency'] ] = $balance_info['credit'];
		}
		else
		{
		    $total_data_credit[ $balance_info['currency'] ] += $balance_info['credit'];
		}
		
		if(isset($total_income[ $balance_info['currency'] ] ))
		{
		    $total_income[ $balance_info['currency'] ]    = $total_income[ $balance_info['currency'] ] + $balance_info['credit'];
		    $total_balance[ $balance_info['currency'] ]    = $total_balance[ $balance_info['currency'] ] + $balance;
		}
        else
        {
            $total_income[ $balance_info['currency'] ] = $balance_info['credit'];
            $total_balance[ $balance_info['currency'] ] = $balance;
        }
            
		
		?>
		<tr>
			<td style="font-size:12px;text-align: center;border-width: 1px;">{{ ( $account_receivable > 0  && isset( $accounts_array[ $account_receivable ] )  ) ? $accounts_array[ $account_receivable ]['aa_account_ref'] . " - " . $accounts_array[ $account_receivable ]['aa_account_label'] : "N/A" }}</td>
			<td style="font-size:12px;text-align: center;border-width: 1px;">{{ $code }}</td>
			<td style="font-size:12px;text-align: center;border-width: 1px;">{{ $date_creation }}</td>
			<td style="font-size:12px;text-align: center;border-width: 1px;">{{ $mov_desc }}</td>
			<td style="font-size:12px;text-align: center;border-width: 1px;"><span class="m--font-success">{{ number_format($balance_info['debit'],2) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
			<td style="font-size:12px;text-align: center;border-width: 1px;"><span class="m--font-success">{{ ( $balance_info['credit'] > 0 ) ? number_format($balance_info['credit'],2) : 0 }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
			<td style="font-size:12px;text-align: center;border-width: 1px;"><span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">{{  number_format($balance,2) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
		</tr>
		@endforeach
		@endforeach
		@endforeach
	</tbody>
</table>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
	<td style="width:33%;text-align: left;" >
			@foreach($total_data_debit as $currency_code => $balance )
			<span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">Total Debit <b>{{ $currency_code }}</b> : {{ number_format($balance,2) }}</span><br/>
		@endforeach
	</td>
	<td style="width:34%" ></td>
	<td style="width:33%;text-align: left;" >
	
	@foreach($total_data_credit as $currency_code => $balance )
			<span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">Total Credit <b>{{ $currency_code }}</b> : {{ number_format($balance,2) }}</span><br/>
		@endforeach
	</td>
	</tr>
</table>

</body>
</html>