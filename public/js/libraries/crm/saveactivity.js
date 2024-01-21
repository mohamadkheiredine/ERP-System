/**
 * 
 */
$(function(){
	$.desc_editor;
	$.result_editor;
	 ClassicEditor
     .create( document.querySelector( '#CA_ACTIVITY_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    }).catch( error => {
         console.error( error );
    });
	 
	 ClassicEditor
     .create( document.querySelector( '#CA_ACTIVITY_RESULT' ) )
     .then( newEditor => {
        $.result_editor = newEditor;
    }).catch( error => {
         console.error( error );
     });
	 $('select').select2();
	 
	 new tempusDominus.TempusDominus(document.getElementById('CA_ACTIVITY_DATE'),{
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
	 $("#BTN_SAVE_ACTIVITY").on("click",leads_module.SaveLeadActivityInfo);
})