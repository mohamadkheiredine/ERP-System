/**
 *
 */
$(function(){
	$("#BTN_SAVE_STOCK").on('click',products_module.GenerateStockInfo);
	$("#FK_WAREHOUSE_ID").on('change',products_module.GetZonesDropDown);
	products_module.GetZonesDropDown();


		products_module.DisplayProductInfo();
	var exchange_rate = $("#IS_STOCK_EXCHANGE_RATE").val();
	if(exchange_rate == 0 || exchange_rate == '')
	{
		products_module.DisplayExchangeRate();
	}

	$('select[name=p_id]').on('change',products_module.DisplayProductInfo);
	$('#STOCK_QUANTITY').on('keyup',products_module.CalculateTotalPurchaseStock);
	$('#IS_PRICE_STOCK').on('keyup',products_module.CalculateTotalPurchaseStock);
	$('#IS_SELLING_STOCK').on('keyup',products_module.CalculateTotalPurchaseStock);
	$('#IS_STOCK_CURRENCY').on('change',products_module.DisplayExchangeRate);
	$('#PRINT_LABELS').on('click',products_module.OpenProductLabels);
	$('#AddUnit').on('click',products_module.ManageStockUnitIds);
	$('input[name=is_discount]').on('keyup',products_module.CalculateDiscountedPrice);
	$('input[name=is_discount]').on('blur',products_module.CalculateDiscountedPrice);
})
