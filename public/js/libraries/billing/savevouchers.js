$(function(){
	$('select').select2();
	ClassicEditor
     .create( document.querySelector( '#PV_VOUCHER_DESCRIPTION' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	$("#PV_CREATION_DATE").datepicker({ 
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	$('#PV_CREATION_DATE').on('changeDate', function() {
	   var current_date = $('#PV_CREATION_DATE').val();
	   
	   vouchers_module.GenerateVoucherCode(current_date);
	});
	$("#BTN_SAVE_VOUCHER").on("click",vouchers_module.SavePaymentVoucherInfo);
	$("#BTN_ADD_EXTENSION").on("click",vouchers_module.AddVoucherExtension);
	$(".LstExtensionVouchers").on("click",".DeleteExtRow",vouchers_module.DeleteExtRow);
	$(".LstExtensionVouchers").on("click",".EditExtRow",vouchers_module.EditExtRow);
	vouchers_module.DisplayListExtensions();
})