$(function(){
	$('select').select2();
	ClassicEditor
     .create( document.querySelector( '#PJ_VOUCHER_DESCRIPTION' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	$("#PJ_CREATION_DATE").datepicker({ 
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	$("#BTN_SAVE_VOUCHER").on("click",vouchers_module.SaveJournalVoucherInfo); 
})