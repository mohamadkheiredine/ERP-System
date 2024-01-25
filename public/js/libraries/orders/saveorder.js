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
	 
	 new tempusDominus.TempusDominus(document.getElementById('SO_ORDER_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true, 
			      clock: false,
			      hours: false,
			      minutes: false,
			      seconds: false,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "L"
			 
		 }
	});
	 new tempusDominus.TempusDominus(document.getElementById('SO_DELIVERY_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true, 
			      clock: false,
			      hours: false,
			      minutes: false,
			      seconds: false,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "L"
			 
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