/**
 * 
 */

$.editor
$(function(){
	services_module.DisplayListPaymentTypesAccounting();
	 $("#BTN_ADD_PAYMENT_TYPE").on('click',function() {
			$("#PaymentType").modal('toggle');
	 });
	
	 ClassicEditor
     .create( document.querySelector( '#CS_SERVICE_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $("select").select2();
	 $("select[name=aa_parent_account]").select2('destroy');
	 $("#BTN_SAVE_SERVICE").on('click',services_module.SaveServiceInfo);
	 $("#BTN_ADD_ACCOUNT").on('click',services_module.AddNewAccount);
	 $("#ADD_PURCHASE_ACCOUNT").on('click',services_module.OpenAddNewAccount);
	 $("#ADD_SALES_ACCOUNT").on('click',services_module.OpenAddNewAccount);
	 $("#BTN_SAVE_PT").on('click',services_module.SavePaymentType);
	 $(".PaymentTypeAccounting").on('click',"a[id*=EDIT_SPT_]",services_module.OpenEditPaymentTypePopup);
	 $(".PaymentTypeAccounting").on('click',"a[id*=DELETE_SPT_]",services_module.DeletePaymentTypeData);
	 

	  $('.ValidatePaymentType').bootstrapSwitch();
})