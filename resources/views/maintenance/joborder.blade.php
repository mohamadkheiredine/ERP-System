<?php 
use App\models\Inventory\Products;
use App\models\CRM\CRMServices;
{
    $total_job_cost = 0;
}
?>
<html>
	<head>
		<title>Maintenance Job Request</title>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="invoice">
        
            <div class="toolbar hidden-print">
                <hr>
            </div>
            <div class="invoice overflow-auto">
                <div style="min-width: 600px">
                    <header>
                        <div class="row">
                            <div class="col-md-12">
                            	<table border="0" width="100%" style="background-color:white;">
                            		<tr>
                            			<td width="50%" align="left"> <img src="{{ $company_logo  }}" style="width:150px;" data-holder-rendered="true" /></td>
                            			<td width="50%" align="right">  <img id="BARCODE_IMG" src="data:image/png;base64,{{ $job_info->j_barecode_img }}" alt="barcode" height="50" width="150"   /></td>
                            		</tr>
                            		<tr>
                            			<td width="50%" align="left">
                            			</td>
                            			<td width="50%" align="right">
                        					<div class="company-details">
                                    		 	<h2 class="name">
                                                    {{ $company_info->cd_company_name }}
                                                </h2>
                                                <div>{{ $company_info->cd_company_address }}</div>
                                                <div>{{ $company_info->cd_company_phone }}</div>
                                                <div>{{ $company_info->cd_company_email }}</div>
                                           	</div>
                            			</td>
                            		</tr>
                            	</table>
                                
                            </div> 
                        </div>
                    </header>
                    <main>
                    	 <div class="row">
                            <div class="col-md-12">
                            	<table border="0" width="100%" style="background-color:white;">
                            		<tr>
                            			<td width="50%">
                            				<div class="col-md-6 invoice-to">
                                                <div class="text-gray-light">Job Request For:</div>
                                                <h2 class="to">{{ $client_name }}</h2>
                                                <div class="address">{{ $client_address }}</div>
                                                <div class="email">{{ $client_email }}</div>
                                                <div class="phone">{{ $client_phone }}</div>
                                            </div>
                            			</td>
                            			<td width="50%">
                            					 <div class="col-md-6 invoice-details">
                              					  <h4 class="invoice-id">Job Order #{{ $job_info->j_job_code }}</h4>
                                                <div class="date">Date of job: {{ date( "d/m/Y",strtotime( $job_info->j_date_creation ) ) }}</div>
                                                <div class="date">Due Date: {{ date( "d/m/Y",strtotime( $job_info->j_due_date ) ) }}</div>
                                            </div>
                            			</td>
                            		</tr>
                            	</table>
                                
                            </div> 
                    	</div> 
                        <div class="row">
                        	<div class="col">
                        		 	<table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">item Name</th>
                                          <th scope="col">Quantity</th>
                                          <th scope="col">Cost Total</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      	<?php foreach( $list_job_items as $index => $job_item ){ ?>
                                      	<?php 
                                      	     $item_type  = $job_item->ji_item_type;
                                      	     $item_id    = $job_item->ji_item_id;
                                      	     if($item_id == 1)
                                      	     {
                                      	         $product_info = Products::find($item_id);    
                                      	     }
                                      	     else 
                                      	     {
                                      	         $service_info = CRMServices::find($item_id);
                                      	     }
                                      	
                                      	?>
                                      	
                                         <tr>
                                          <th scope="row">1</th>
                                          <td>{{ isset($product_info) ? $product_info->p_product_name : $service_info->cs_service_title }}</td>
                                          <td>{{ $job_item->ji_quantity }}</td>
                                          <td>{{ $job_item->ji_item_cost }}&nbsp;&nbsp;<b>{{ $job_item->Currency->cc_currency_code }}</b></td>
                                        </tr>
                                        <?php $total_job_cost = $total_job_cost + ( $job_item->ji_item_cost *  $job_item->ji_quantity ); ?>
                                        <?php } ?>
                                      </tbody>
                                      <tfoot>
                                      	<th colspan="3">Total</th>
                                      	<th>
                                      		{{ $total_job_cost }}&nbsp;{{ $job_info->Currency->cc_currency_code }}
                                      	</th>
                                      </tfoot>
                                    </table>
                        	</div>
                        </div>
                        <div style="width:100%;height:20px;">&nbsp;</div>
                        <div class="notices">
                      
                        </div>
                    </main>
                    <footer>
                    	<div class="row">
                    		<div class="col-md-12" align="left" style="text-align: left">
                             
                    		</div>
                    	</div>
                    </footer>
                </div>
                <div></div>
            </div>
        </div>
	</body>
</html>