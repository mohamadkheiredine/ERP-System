/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SO_OPERATION_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
 new tempusDominus.TempusDominus(document.getElementById('SO_OPERATION_DATE'),{
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
			 format : "MM/dd/yyyy"
			 
		 }
	});
         new tempusDominus.TempusDominus(document.getElementById('SO_OPERATION_TIME'),{
		 display: {
			  components: {
			      calendar: false,
			      date: false,
			      month: false,
			      year: false,
			      decades: false, 
			      clock: true,
			      hours: true,
			      minutes: true,
			      seconds: true,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "HH:mm:ss"
			 
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
			      clock: true,
			      hours: true,
			      minutes: true,
			      seconds: true,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "yyyy-MM-d HH:mm:ss"
			 
		 }
	});
	
	 operations_module.DisplayOperationTypeFields();
         operations_module.DisplayListOperationOrders();
	 $("#BTN_SAVE_OPERATION").on("click",operations_module.SaveOperationInfo);
	 $("#BTN_ADD_ORDER").on("click",operations_module.AddOperationOrder);
	 $("#SO_OPERATION_TYPE").on("change",operations_module.DisplayOperationTypeFields);
	 $(".dropdown-item").on("click",operations_module.QuickActions);
//	 $("#OPERATION_INFO").on("change","#SO_WAREHOUSE_SOURCE",operations_module.DisplayListOperationProducts);
//	 $("#OPERATION_INFO").on("click","#ADD_NEW_PRODUCT",operations_module.AddNewProductRow);
//	 $("#OPERATION_INFO").on("click",".deleteRow",operations_module.DeleteProductRow);
//	 $("#OPERATION_INFO").on("click","#SO_WAREHOUSE_SOURCE",operations_module.GetVehiculeDropdown);
	 
})