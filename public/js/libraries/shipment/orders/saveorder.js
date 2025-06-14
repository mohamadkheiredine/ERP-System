$(function(){

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
		orders_module.DisplayListOrderCategories();
		$('#BTN_ADD_CATEGORY').on('click',orders_module.OpenAddOrderPackageModal);
		$('#BTN_SAVE_CATEGORY').on('click',orders_module.AddOrderPackage);
	}
	 //LstProducts
	$('#SO_PRODUCT_CATEGORY').on('change',orders_module.getPackingPrice);
	$('#SO_PRODUCT_COST').on('blur',orders_module.CheckStockPriceValue);
	$('#BTN_SAVE_ORDER').on('click',orders_module.SaveOrdersInfo);
	$('#BTN_PAY_ORDER').on('click',orders_module.PayPaymentOrder);
	$('#LstPackingCategories').on('click','a[id*=DELETE_CATEGORY_]' ,orders_module.DeleteCategoryFromOrder);

})
