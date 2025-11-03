<?php
/***********************************************************
lstaccountstatment.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Mar 28, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :
debit : +
credit : -
***********************************************************/


$total_debit    = 0;
$total_credit   = 0;
$total_data_debit   = array();
$total_data_credit   = array();
$total_income   = array();
$total_balance  = array();
$total_amount = 0;

?>


<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th style="width:5%">Currency</th>
			<th style="width:30%">Label</th>
			<th style="width:10%">Account Code</th>
			<th style="width:20%">Debit</th>
			<th style="width:15%">Credit</th>
			<th style="width:15%">Balance</th>
			<th style="width:3%">Action</th>
		</tr>
	</thead>
	<tbody >
		@foreach($lst_accounts as $index => $account_info)
		<tr class='grouprow' data-account_id="{{ $account_info->tm_sub_ledger_account }}" data-currency_id="{{ $account_info->cc_id }}">
			<td>{{  $account_info->cc_currency_code }}</td>
			<td>{{  $account_info->aa_account_label }}</td>
			<td>{{  $account_info->aa_account_ref }}</td>
			<td><span class="m--font-success">{{ number_format($account_info->total_debit,2) }}&nbsp;<b>{{ $account_info->cc_currency_code }}</b></span></td>
			<td><span class="m--font-success">{{ ( $account_info->total_credit > 0 ) ? number_format($account_info->total_credit,2) : 0 }}&nbsp;<b>{{ $account_info->cc_currency_code }}</b></span></td>
			<td><span class="{{ $account_info->total_balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">{{  number_format($account_info->total_balance,2) }}&nbsp;<b>{{ $account_info->cc_currency_code }}</b></span></td>
		</tr>
		@endforeach
	</tbody>
    <tfoot>
        @foreach($lst_total_amounts_by_currencies as $currency => $total)
            <tr>
                <th colspan="5" class="text-end">Total ({{ $currency }})</th>
                <th>{{ number_format($total, 2) }} {{ $currency }}</th>
                <th></th>
            </tr>
        @endforeach
    </tfoot>

</table>
