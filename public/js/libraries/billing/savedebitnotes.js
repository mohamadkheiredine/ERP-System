/**
 * 
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#DN_DEBIT_NOTES' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
		
	 new tempusDominus.TempusDominus(document.getElementById('DN_CREATION_DATE'),{
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
	
	$("#BTN_SAVE_DNOTE").on("click",debitnotes_module.SaveDebitNoteInfo);
})