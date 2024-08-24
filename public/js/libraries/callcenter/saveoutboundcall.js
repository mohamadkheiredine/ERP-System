$(function(){
	ClassicEditor
    .create( document.querySelector( '#OC_CALL_OUTCOME' ) )
    .then( newEditor => {
       $.outcome_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    
    
    ClassicEditor
    .create( document.querySelector( '#OC_NOTES' ) )
    .then( newEditor => {
       $.notes_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
    
    
     new tempusDominus.TempusDominus(document.getElementById('OC_CALL_DATE'),{
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
        
          new tempusDominus.TempusDominus(document.getElementById('OC_CALL_START_TIME'),{
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
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "HH:mm:ss"
			 
		 }
	});
        
        
          new tempusDominus.TempusDominus(document.getElementById('OC_CALL_END_TIME'),{
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
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "HH:mm:ss"
			 
		 }
	});
    
	
	$('#BTN_SAVE_CALL').on('click',outboundcalls_module.SaveOutboundCallInfo); 
});