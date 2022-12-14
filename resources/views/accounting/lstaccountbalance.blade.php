<?php
/***********************************************************
lstaccountbalance.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Oct 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

$total_debit    = 0;
$total_credit   = 0;
$total_income   = array();
$total_balance  = array();
?>


<table class="table table-bordered table-hover">
	<thead>
		<tr> 
			<th style="width:20%">Account</th>
			<th style="width:20%">Debit</th>
			<th style="width:30%">Credit</th>
			<th style="width:30%">Balance</th>
		</tr>
	</thead>
	<tbody >
		@foreach($account_balance as $curremcy => $balance )
		@foreach($balance as $account_id => $balance_info )
		<?php 
		$total_debit          = $total_debit + $balance_info['debit'];
		$total_credit         = $total_credit+ $balance_info['credit'];
		$balance              = $balance_info['debit'] - $balance_info['credit'];
		$account_payable      = $balance_info['account_payable'];
		$account_receivable   = $balance_info['account_receivable'];
		
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
			<td>{{ ( isset($accounts_array[ $account_receivable ]) ) ?  $accounts_array[ $account_receivable ]['aa_account_ref'] . " - " . $accounts_array[ $account_receivable ]['aa_account_label'] : "N/A" }}</td>
			<td><span class="m--font-success">{{ number_format($balance_info['debit']) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
			<td><span class="{{ $balance_info['credit'] == 0 ? 'm--font-success' : 'm--font-danger' }}">{{ ( $balance_info['credit'] > 0 ) ? number_format(-1 * $balance_info['credit']) : 0 }}&nbsp;<b>{{ $balance_info['currency'] }}</span></td>
			<td><span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">{{  number_format($balance) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
		</tr>
		@endforeach
		@endforeach
	</tbody>
</table>

<div class="row">
	<div class="col-md-4">
		@foreach($total_income as $currency_code => $balance )
			<span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">Income In <b>{{ $currency_code }}</b> : {{ number_format($balance) }}</span><br/>
		@endforeach
	</div>
	<div class="col-md-4"></div>
	<div class="col-md-4">
			@foreach($total_balance as $currency_code => $balance )
			<span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">Total Balance In <b>{{ $currency_code }}</b> : {{ number_format($balance) }}</span><br/>
		@endforeach
	</div>
</div>