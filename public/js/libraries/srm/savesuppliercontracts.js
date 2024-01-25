/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SC_CONTRACT_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	$('select').select2();
	
	new tempusDominus.TempusDominus(document.getElementById('SC_CONTRACT_DATE'),{
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
	
	new tempusDominus.TempusDominus(document.getElementById('SC_CONTRACT_DELIVERY_DATE'),{
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
	
	 $("#BTN_SAVE_CONTRACT").on("click",suppliercontracts_module.SaveSupplierContractInfo);
	 var sc_id = $("#SC_ID").val();
	 
	 if(sc_id != null)
	 {
		 suppliercontracts_module.DisplayContractProducts();
		 $("#BTN_ADD_PRODUCT").on("click",suppliercontracts_module.InsertRawProductToContract);
	 }
	 
})