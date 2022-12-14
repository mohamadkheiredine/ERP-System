	<style>
#invoice-POS{
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 44mm;
  background: #FFF;
  
}

::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .6em;}
.tabletitle h2{
    font-size:12px;
}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  //padding: 5px 0 5px 15px;
  //border: 1px solid #EEE
}
.tabletitle{
  //padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 30mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

	</style>
	<script src="{{ url('default/assets/plugins/jquery.min.js') }}" type="text/javascript"></script>
	<script>
		$(function(){
			window.print();
		})
	</script>
  <div id="invoice-POS">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2>{{$company_info->cd_company_name}}</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
        <h2>Contact Info</h2>
        <p> 
            Address : {{$user_info->u_address}}</br>
            Email   : {{$user_info->u_email}}</br>
            Phone   : {{$user_info->u_mobile}}</br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    <div id="bot">

					<div id="table">
						<table>
							<tr class="tabletitle">
								<td class="item"><b>Item</b></td>
								<td class="Hours"><b>Qty</b></td>
								<td class="Rate"><b>Sub Total</b></td>
							</tr>
							@foreach($lst_order_items as $index => $order_item)
							<tr class="service">
								<td class="tableitem"><p class="itemtext">{{ $order_item->fk_product_id > 0  ?  $order_item->Products->p_product_name : $order_item->so_unit_label }}</p></td>
								<td class="tableitem"><p class="itemtext">{{ $order_item->so_product_quantity }}</p></td>
								<td class="tableitem"><p class="itemtext">{{ $order_item->so_product_cost }}&nbsp;<b>{{ $order_info->Currency->so_product_cost }}</b>&nbsp;</p></td>
							</tr>
							@endforeach
							@if($tax_total > 0)
							<tr class="tabletitle">
								<td></td>
								<td class="Rate"><b>tax</b></td>
								<td class="payment"><b>{{ $tax_total }}</b></td>
							</tr>
							@endif
							<tr class="tabletitle">
								<td></td>
								<td class="Rate"><b>Total</b></td>
								<td class="payment"><b>{{ ( $cost_total + $tax_total ) }}</b>&nbsp;<b>{{ $order_info->Currency->cc_currency_code }}</b>&nbsp;</td>
							</tr>

						</table>
					</div><!--End Table-->

					<div id="legalcopy">
						<p class="legal"><strong>Thank you for your business!</strong>  Payment is expected within 31 days; please process this invoice within that time. There will be a 5% interest charge per month on late invoices. 
						</p>
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
