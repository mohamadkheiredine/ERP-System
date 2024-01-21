/**
 * 
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#IN_TRANSFER_NOTES' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	 new tempusDominus.TempusDominus(document.getElementById('IN_TRANSFER_DATE'),{
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
	$("#BTN_SAVE_NOTE").on("click",inttransfers_module.SaveTransferNoteInfo);
})