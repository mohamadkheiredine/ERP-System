$(function(){
	$("#BTN_SAVE_TRANSFER").on("click",products_module.ApplyTransferStock);
	$("#BTN_ADD_ITEM").on("click",products_module.AddTransferItems);
	$("#MP_PRODUCT_ID").on("change",products_module.DisplayProductDescriptionInStockTransfer);
	$("#MP_PRODUCT").on("change",products_module.DisplayPProductDescriptionInStockTransfer);


});
