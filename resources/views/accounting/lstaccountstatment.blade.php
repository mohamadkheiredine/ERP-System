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
 
?>
@if($show_back == 1)
<div class="row">
	<div class="col-md-2" align="left">
		<button type="button" name="btn_back" class="btn btn-success">back</button>
	</div>
	<div class="col-md-6" align="left"></div>
	<div class="col-md-4" align="right">
		<div class="m-dropdown m-dropdown--inline  m-dropdown--arrow" data-dropdown-toggle="click">
			<a href="#" class="m-dropdown__toggle btn btn-success dropdown-toggle">
				Actions
			</a>
			<div class="m-dropdown__wrapper">
				<span class="m-dropdown__arrow m-dropdown__arrow--left"></span>
				<div class="m-dropdown__inner">
					<div class="m-dropdown__body">
						<div class="m-dropdown__content">
							<ul class="m-nav"> 
								<li class="m-nav__item">
									<a href="#" id="EXPORT_CURRENCY" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-share"></i>
										<span class="m-nav__link-text">
											Export <span class="CurrencyLabel"> Save </span> As PDF
										</span>
									</a>
								</li>
								<li class="m-nav__item">
									<a href="#" id="EXPORT_ALL" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-chat-1"></i>
										<span class="m-nav__link-text">
											Export All Currencies
										</span>
									</a>
								</li> 
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	 
	</div>
</div>
<input type="hidden" name="account_id" value="{{ $account_id }}" />
<input type="hidden" name="currency_id" value="{{ $currency_info->cc_id }}" />
@endif

<table class="table table-bordered table-hover">
	<thead>
		<tr> 
			<th style="width:20%">Account</th>
			<th style="width:10%">Account Code</th>
			<th style="width:10%">Date</th>
			<th style="width:30%">Label</th>
			<th style="width:20%">Debit</th>
			<th style="width:15%">Credit</th>
			<th style="width:15%">Balance</th>		</tr>
	</thead>
	<tbody > 
		@foreach($account_balance as $curremcy => $balance )
		@foreach($balance as $account_id => $lst_balance )
		@foreach($lst_balance as $index => $balance_info )
		<?php  
		$total_debit          = $balance_info['debit'];
		$total_credit         = $balance_info['credit']; 
		$account_payable      = $balance_info['account_payable'];
		$account_receivable   = $balance_info['account_receivable'];
		$date_creation        = $balance_info['date_creation'];
		$code                 = $balance_info['code'];
		$mov_desc             = isset($balance_info['mov_desc']) ? $balance_info['mov_desc'] : "";
		$balance              = $balance_info['balance'];
		 
		
		
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
		
		if(isset($total_income[ $balance_info['currency'] ] ) && isset($total_balance[ $balance_info['currency'] ]))
		{
		    
		    $total_income[ $balance_info['currency'] ]    = $total_income[ $balance_info['currency'] ] + $balance_info['credit'];
		    $total_balance[ $balance_info['currency'] ]    = $total_balance[ $balance_info['currency'] ] + $balance['balance'];
		}
        else
        {
            $total_income[ $balance_info['currency'] ] = $balance_info['credit'];
            $total_balance[ $balance_info['currency'] ] = $balance['balance'];
        } 
        
		?>
		<tr class="Transaction" data-tm_id="{{ $balance_info['tm_id'] }}" data-tran_id="{{ $balance_info['trans_id'] }}">
			<td>{{ ( $account_receivable > 0  && isset( $accounts_array[ $account_receivable ] )  ) ? $accounts_array[ $account_receivable ]['aa_account_ref'] . " - " . $accounts_array[ $account_receivable ]['aa_account_label'] : "N/A" }}</td>
			<td>{{ $code }}</td>
			<td>{{ $date_creation }}</td>
			<td>{{ $mov_desc }}</td>
			<td><span class="m--font-success">{{ number_format($balance_info['debit'],2) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
			<td><span class="m--font-success">{{  number_format($balance_info['credit'],2) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
			<td><span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">{{  number_format($balance,2) }}&nbsp;<b>{{ $balance_info['currency'] }}</b></span></td>
		</tr>
		@endforeach
		@endforeach
		@endforeach
	</tbody>
</table>

 <div class="row">
	<div class="col-md-4">
		@foreach($total_data_debit as $currency_code => $balance )
			<span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}"> Total Debit <b>{{ $currency_code }}</b> : {{ number_format($balance,2) }}</span><br/>
		@endforeach
	</div>
	<div class="col-md-4"></div>
	<div class="col-md-4">
			@foreach($total_data_credit as $currency_code => $balance )
			<span class="{{ $balance >= 0 ? 'm--font-success' : 'm--font-danger' }}">Total Credit <b>{{ $currency_code }}</b> : {{ number_format($balance,2) }}</span><br/>
		@endforeach
	</div>
</div>