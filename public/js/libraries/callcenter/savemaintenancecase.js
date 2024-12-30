$(function(){
	ClassicEditor
    .create( document.querySelector( '#CC_CASE_DESCRIPTION' ) )
    .then( newEditor => {
       $.case_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    
    
    ClassicEditor
    .create( document.querySelector( '#CC_RESOLUTION_NOTES' ) )
    .then( newEditor => {
       $.res_notes = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    
    
     new tempusDominus.TempusDominus(document.getElementById('CC_CASE_DATE'),{
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
       
       new tempusDominus.TempusDominus(document.getElementById('CC_CASE_TIME'),{
		 display: {
			  components: {
			      calendar: false,
			      date: false,
			      month: false,
			      year: false,
			      decades: false, 
			      clock: true,
			      hours: true,
			      minutes: true,
			      seconds: true,
			      useTwentyfourHour: true
			    }
		 },
		 localization: {
			 format : "HH:mm:ss"
			 
		 }
	});
	
	$('#BTN_SAVE_CASE').on('click',mcases_module.SaveMaintenanceCaseInfo); 
	$('#CC_CLIENT_CODE').on('change',mcases_module.getAccountCaseInfo); 
});