/**
 * 
 */

$(function(){
	$.desc_editor;
	$.result_editor;
	 ClassicEditor
     .create( document.querySelector( '#CA_APPOINTMENT_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    }).catch( error => {
         console.error( error );
    });
	 
	 ClassicEditor
     .create( document.querySelector( '#CA_APPOINTMENT_RESULT' ) )
     .then( newEditor => {
        $.result_editor = newEditor;
    }).catch( error => {
         console.error( error );
     });
	 $('select').select2();
		new tempusDominus.TempusDominus(document.getElementById('CA_APPOINTMENT_DATE'),{
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

		new tempusDominus.TempusDominus(document.getElementById('ca_appointment_start_time'),{
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
				 format : "LT"
				 
			 }
		});
		new tempusDominus.TempusDominus(document.getElementById('ca_appointment_end_time'),{
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
				 format : "LT"
				 
			 }
		});
	 $("#BTN_SAVE_APPOINTMENT").on("click",leads_module.SaveAppointmentInfo);
});