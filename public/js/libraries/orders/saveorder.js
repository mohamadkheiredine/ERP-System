$(function(){
	if( $("input[name=so_whole_sale]:checked").length == 1 )
	{
		$('#SO_ORDER_CUSTOMER').attr('disabled','disabled');
		$('#SO_VENDOR_ID').removeAttr('disabled');
	}
	else
	{
		$('#SO_ORDER_CUSTOMER').removeAttr('disabled');
		$('#SO_VENDOR_ID').attr('disabled','disabled');
	}
	
	 ClassicEditor
     .create( document.querySelector( '#SO_ORDER_NOTE' ) )
     .then( newEditor => {
        $.order_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('#SO_ORDER_DATE').datepicker({
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('#SO_DELIVERY_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('select').select2();
	 
	 let so_id = $('input[name=so_id]').val();
	if(so_id != null)
	{
		orders_module.DisplayListOrderProducts();
		$('#BTN_ADD_PRODUCT').on('click',orders_module.OpenAddOrderProductsModal);
		$('#BTN_SAVE_PRODUCT').on('click',orders_module.AddOrderProducts);
	}
	 //LstProducts
	$('#ORDER_PRODUCT').on('change',orders_module.getProductPrice);
	$('#SO_PRODUCT_COST').on('blur',orders_module.CheckStockPriceValue);
	$('#BTN_SAVE_ORDER').on('click',orders_module.SaveOrdersInfo);
	$('#BTN_PAY_ORDER').on('click',orders_module.PayPaymentOrder);
	 $('#SO_PRODUCT_SERIAL').on('blur',orders_module.getStockInformation);
	
		
		
		$(".WholeSaleSpan").on('click',function(){
			if( $("input[name=so_whole_sale]:checked").length == 1 )
			{
				$('#SO_ORDER_CUSTOMER').removeAttr('disabled');
				$('#SO_VENDOR_ID').attr('disabled','disabled');
			}
			else
			{
				$('#SO_ORDER_CUSTOMER').attr('disabled','disabled');
				$('#SO_VENDOR_ID').removeAttr('disabled');
			}
		})
		
})