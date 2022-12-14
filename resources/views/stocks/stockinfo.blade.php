<div className="row">
	<div className="col-md-12">
		<label>Product Name:</label>&nbsp;{{ $product_info->p_product_name }}
	</div>
</div>
<div className="row">
	<div className="col-md-12">
		<label>Product Barcode:</label>&nbsp;{{ $stock_info->is_stock_uid }}
	</div>
</div>
<div className="row">
	<div className="col-md-12">
		<label>Product Price:</label>&nbsp;{{ $stock_info->is_price_stock }}
	</div>
</div>
<div className="row">
	<div className="col-md-12">
		<label>Supplier:</label>&nbsp;{{ $supplier_info->is_supplier_name }}
	</div>
</div>
<div className="row">
	<div className="col-md-12">
		<label>Stock Added:</label>&nbsp;{{ date("Y-m-d",strtotime($stock_info->is_creation_date)) }}
	</div>
</div>
<div className="row">
	<div className="col-md-12">
		<label>Order Barcode:</label>&nbsp;{{ $order_barcode }}
	</div>
</div>
<div className="row">
	<div className="col-md-12">
		<label>Order Date:</label>&nbsp;{{ ($order_date != '') ? date("Y-m-d",strtotime($order_date)) : ""  }}
	</div>
</div>