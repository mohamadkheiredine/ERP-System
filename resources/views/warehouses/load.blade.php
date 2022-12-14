<?php
/***********************************************************
load.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 26, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Display list of all stocks in this current warehouse
***********************************************************/

?>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
    	<div class="m_datatable" id="LstStockWarehouse">
        <table class="m-datatable" id="StockWarehouseDatatables" width="100%">
            		<thead>
            			<tr>
            				<th title="#">#</th>
            				<th title="Id"> ID </th>
            				<th title="Zone Name"> Zone Name </th>
            				<th title="Quantity"> Quantity </th>
            				<th title="Product Name"> Product Name </th> 
            				<th title="Price"> Price </th> 
            			</tr>
            		</thead>
            		<tbody>
            			  	@foreach( $lst_stock as $index => $si_info )
                            <tr  class="odd gradeX" data-is_id="{{ $si_info->is_id }}">
                            	<td><input type="checkbox" name="ck_is_{{ $si_info->is_id }}" id="CK_IS_{{ $si_info->is_id }}" class="checkboxes" value="{{ $si_info->ci_id }}" /></td>
                               <td>{{ $si_info->is_id }}</td>
                               <td>{{ ( $si_info->fk_zone_id != 0 ) ? $si_info->Zones->wz_zone_label : "N/A" }}</td>
                               <td>{{ $si_info->is_quanity }}</td>
                               <td>{{ $si_info->products->p_product_name }}</td>
                               <td>{{ $si_info->is_price_stock  }}&nbsp;<b>{{ $si_info->Currency->cc_currency_code }}</b></td> 
                            </tr>
                            @endforeach
            		</tbody>
            </table>
        </div>
	</div>
	<div class="col-md-1"></div>
</div>
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-5">
		<div class="m-portlet m-portlet--tab">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
							<i class="la la-gear"></i>
						</span>
						<h3 class="m-portlet__head-text">
							Warehouse Load By Zone
						</h3>
					</div>
				</div>
			</div>
			<div class="m-portlet__body">
				<div id="m_warehouseloadzonechart" style="height: 500px;"></div>
			</div>
		</div>
	</div>
	<div class="col-md-5"></div>
	<div class="col-md-1"></div>
</div>