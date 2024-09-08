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
    
    
     new tempusDominus.TempusDominus(document.getElementById('CC_CASE_DEADLINE'),{
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
       
       new tempusDominus.TempusDominus(document.getElementById('CC_RESOLUTION_DATE'),{
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
	
	$('#BTN_SAVE_CASE').on('click',mcases_module.SaveMaintenanceCaseInfo); 
});