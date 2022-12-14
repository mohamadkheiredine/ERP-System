/**
 * 
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#BR_RECEIPT_NOTE' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	$("#BR_RECEIPT_DATE").datepicker({ 
		showButtonPanel: true,
		todayBtn : true,
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});

	$('#BR_RECEIPT_DATE').on('changeDate', function() {
	   var current_date = $('#BR_RECEIPT_DATE').val();
	   
	   receipts_module.GenerateReceiptCode(current_date);
	});
	$("#BTN_SAVE_RECEIPT").on("click",receipts_module.SaveReceiptInfo);
})