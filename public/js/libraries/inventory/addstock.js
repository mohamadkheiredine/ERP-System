/**
 * 
 */
$(function(){
 
	products_module.DisplayProductInfo();
	$("#BTN_CREATE_STOCK").on('click',products_module.CreateProductStock);
	$("#FK_WAREHOUSE_ID").on('change',products_module.GetZonesDropDown);
	products_module.GetZonesDropDown();
	var exchange_rate = $("#IS_STOCK_EXCHANGE_RATE").val();
	if(exchange_rate == 0 || exchange_rate == '')
	{
		products_module.DisplayExchangeRate();	
	}
	$('#IS_STOCK_CURRENCY').on('change',products_module.DisplayExchangeRate);
	$('#PRINT_LABELS').on('click',products_module.OpenProductLabels);
	$('select').select2();
	$('#AddUnit').on('click',products_module.ManageStockUnitIds);
	$('input[name=is_discount]').on('keyup',products_module.CalculateDiscountedPrice);
})