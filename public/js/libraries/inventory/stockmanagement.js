/**
 *
 */
$(function(){
	products_module.DisplayListStock();
	$('select[name=stock_warehouse]').on('change',function(){
		$('input[name=page_number]').val(1);
		products_module.DisplayListStock();
	});
	$('select[name=stock_product]').on('change',function(){
		$('input[name=page_number]').val(1);
		products_module.DisplayListStock();
	});
	$('select[name=stock_currency]').on('change',function(){
		$('input[name=page_number]').val(1);
		products_module.DisplayListStock();
	});
	$('input[name=general_search]').on('keyup',function(){
		$('input[name=page_number]').val(1);
		products_module.DisplayListStock();
	});

    $('.SwitchView').on('click',function(){
		$('input[name=list_type]').val($(this).data('view'));
		products_module.DisplayListStock();
	});


    $('#LstProductStocks').on('click',"a[id*=EDIT_STOCK_]",products_module.EditStockInfo);
    $('#LstProductStocks').on('click',"a[id*=DELETE_STOCK_]",products_module.DeleteStockInfo);
});
