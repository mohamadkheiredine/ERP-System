
$(function(){
	 packing_module.DisplayListPacking();
	$("#generalSearch").on('keyup',function(){
		$('input[name=page_number]').val(1);
		$.pagination.twbsPagination('destroy');
		 packing_module.DisplayListPacking();
	});
        $("#PRODUCT_CATEGORY").on('change',function(){
		$('input[name=page_number]').val(1);
		$.pagination.twbsPagination('destroy');
		 packing_module.DisplayListPacking();
	});
	$("#LstPackingAmounts").on('click',"a[id*=EDIT_PACKING_]",packing_module.DisplayEditPackingForm);
	$("#LstPackingAmounts").on('click',"a[id*=DELETE_PACKING_]",packing_module.DeletePackingData);
})