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
	new tempusDominus.TempusDominus(document.getElementById('BR_RECEIPT_DATE'),{
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
			 format : "yyyy-MM-dd"

		 }
	});

	$('#BR_RECEIPT_DATE').on('changeDate', function() {
	   var current_date = $('#BR_RECEIPT_DATE').val();

	   receipts_module.GenerateReceiptCode(current_date);
	});
	$("#BTN_SAVE_RECEIPT").on("click",receipts_module.SaveReceiptInfo);
})
