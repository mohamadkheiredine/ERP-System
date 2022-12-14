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
	$('input[name=sc_contract_date]').datepicker({
		startDate :'+1d',
		todayHighlight: true,
		orientation: "bottom left",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	 $('input[name=sc_contract_delivery_date]').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
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