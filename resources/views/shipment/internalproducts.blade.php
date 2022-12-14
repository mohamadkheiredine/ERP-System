<?php
/***********************************************************
internalproducts.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/
 
?>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered table-hover" id="ProductsRow">
        	<thead>
        		<tr>
        			<th>#</th>
        			<th>Product Name</th>
        			<th>Quantity</th>
        		</tr>
        	</thead>
        	<tbody> 
        		<?php 
        		if($so_id != null)
        		{
        		    foreach ($shipment_operations_products as $key => $op_info) {
            		        ?>
            		     	<tr>
                    			<th scope="row">{{ $op_info->op_id }}</th>
                    			<td>{{ $op_info->fk_product_id }}</td>
                    			<td>{{ $op_info->op_product_quantity }}</td> 
                    		</tr>
            		     <?php
            		  }
        		}
        		
        		?> 
        		<tr>
        			<th scope="row"></th>
        			<td>
        				 <select class="bs-select form-control" name="products[]"  data-actions-box="true">
                            <option value="">-- select one --</option>
                            @foreach ( $lst_products as $key => $prod_info )
                                    <option value="{{ $prod_info->p_id }}">{{ $prod_info->p_product_name }}</option>
                            @endforeach
                        </select>
        			</td>
        			<td><input type="text" name="quantity[]" class="form-control" value="" /></td>
        			<td style="width:4px;vertical-align: middle;">
        			<a href="#"  class="deleteRow" style="text-decoration:none;display:none"><i class="fa fa-close"></i></a>
        			</td>
        		</tr>
        	</tbody>
        </table>
	</div>
</div>
<div class="row">
	<div class="col-md-11">
     
     </div>
     <div class="col-md-1">
     	<a href="#" id="ADD_NEW_PRODUCT" style="text-decoration:none"><i class="flaticon-add-circular-button" alt="Add new Product"></i></a>
     </div>
</div>