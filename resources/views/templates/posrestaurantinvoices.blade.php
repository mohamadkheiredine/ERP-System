
	<?php 
    $image_src_url  = url('/')."/".Config::get('constants.COMPANY_PATH').$company_info->cd_logo_base_src.$company_info->cd_logo_file_name.".".$company_info->cd_logo_file_extension;
    $image_src_path = public_path(). "/" .Config::get('constants.COMPANY_PATH').$company_info->cd_logo_base_src.$company_info->cd_logo_file_name.".".$company_info->cd_logo_file_extension;
    
    if(strlen($company_info->cd_logo_base_src) > 0 ){
        $img_src = $image_src_url;
    }else{
        $img_src = url('images/NoImageAvailable.jpg');
    }	
	
	
	?>
	<html>
		<head>
		
	
	<style>
#invoice-POS{
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 100%;
  background: #FFF;
  
}

/* ::selection {background: #f31544; color: #FFF;} */
/* ::moz-selection {background: #f31544; color: #FFF;} */
/* h1{ */
/*   font-size: 1.5em; */
/*   color: #000; */
/* } */
/* .tabletitle h2{ */
/*     font-size:12px; */
/* } */
/* h3{ */
/*   font-size: 1.2em; */
/*   font-weight: 300; */
/*   line-height: 2em; */
/* } */
/* p{ */
/*   font-size: .7em; */
/*   color: #000; */
/*   line-height: 1.2em; */
/* } */
 
/* #top, #mid,#bot{ /* Targets all id with 'col-' */ */
/*   border-bottom: 1px solid #EEE; */
/* } */



/* .info{ */
/* } */
/* .title{ */
/*   float: right; */
/* } */
/* .title p{text-align: right;}  */
/* table{ */
/*   width: 100%; */
/*   border-collapse: collapse; */
/* } */
/* td{ */
/*   //padding: 5px 0 5px 15px; */
/*   //border: 1px solid #EEE */
/* } */
/* .tabletitle{ */
/*   //padding: 5px; */
/*   background: #EEE; */
/* } */
/* .service{border-bottom: 1px solid #EEE;} */
/* .item{width: 30mm;} */
/* .itemtext{color:black !important;font-size:8px !important;} */

/* #legalcopy{ */
/*   margin-top: 5mm; */
/* } */
.address{
	font-size:8px !important;
}

	</style>
	<script src="{{ url('default/assets/plugins/jquery.min.js') }}" type="text/javascript"></script>
	<script>
		$(function(){
			window.print();
		})
	</script>
		</head>
		<body>
		
  <div id="invoice-POS">
    
    <center id="top">
      <div class="info"> 
	  <img src="{{ $img_src }}" style="width:72px;height:auto" />
        <h2>{{$company_info->cd_company_name}}</h2>
	<span style="font-size:8px">Route Niger en face de </span><br/>
	<span style="font-size:8px">CITE CHINOISE Coleah - Conakry</span><br/>
	<span style="font-size:8px">{{ $order_info->so_order_date }}</span><br/>
	<span style="font-size:8px">FACTURE {{$order_info->so_order_code}}</span><br/>
	<b style="font-size:8px">Tel:+224621061431</b><br/>
	<b style="font-size:8px">Tel:+224621011691</b><br/>
	<b style="font-size:8px">Tel:+224626039659</b><br/>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
        <h2></h2>
      </div>
    </div><!--End Invoice Mid-->
    <div id="bot">
				<hr style="width:100%" />
					<div id="table">
						<table>
							<tr class="tabletitle">
								<td class="item"><b></b></td>
								<td class="Rate"><b></b></td>
								<td class="Rate"><b></b></td>
							</tr>
							@foreach($lst_order_items as $index => $order_item)
								<?php
    								if($order_item == null)
    								    continue;
								?>
							<tr class="service">
								<td class="tableitem"><p class="itemtext" style="font-size:8px">{{ $order_item->quantity }} x </p></td>
								<td class="tableitem" style="font-size:8px">{{ $order_item->product_name }}</td>
								<td class="tableitem"><p class="itemtext" style="font-size:8px">{{ $order_item->product_selling_price }}&nbsp;<b>{{ $order_item->currency_code }}</b>&nbsp;</p></td>
							</tr>
							@endforeach
							@if($order_info->so_delivery_fees > 0 )
							<tr class="tabletitle">
								<td class="Rate" style="font-size:10px"><b>livraison</b></td>
								<td class="payment" style="font-size:10px"><b>{{ $order_info->so_delivery_fees }}</b>&nbsp;&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b>&nbsp;</td>
								<td></td>
							</tr>
							@endif
							@if($order_info->so_extra_charges > 0 )
							<tr class="tabletitle">
								<td class="Rate" style="font-size:10px"><b>Montant supplémentaire</b></td>
								<td class="payment" style="font-size:10px"><b>{{ $order_info->so_extra_charges }}</b>&nbsp;&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b>&nbsp;</td>
								<td></td>
							</tr>
							@endif
							
							<tr class="tabletitle">
								<td class="Rate" style="font-size:10px"><b>Total</b></td>
								<td class="payment" style="font-size:10px"><b>{{ ( $cost_total ) }}</b>&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b>&nbsp;</td>
								<td></td>
							</tr>
						</table>
					</div><!--End Table-->
<hr style="width:100%" />
					<div id="legalcopy">
						<p class="legal"> 
						</p>
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
		
		</body>
	</html>
