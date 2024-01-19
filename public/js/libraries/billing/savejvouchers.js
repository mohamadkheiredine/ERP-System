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
	 
	 new tempusDominus.TempusDominus(document.getElementById('PJ_CREATION_DATE'),{
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
	$("#BTN_SAVE_VOUCHER").on("click",vouchers_module.SaveJournalVoucherInfo); 
})