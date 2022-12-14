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
	 $('#CA_APPOINTMENT_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('input[name=ca_appointment_start_time]').timepicker({
		 minuteStep: 1,
		 defaultTime: '',
		 showSeconds: true,
		 showMeridian: false,
		 snapToStep: true
	 });
	 $('input[name=ca_appointment_end_time]').timepicker({
         minuteStep: 1,
         defaultTime: '',
         showSeconds: true,
         showMeridian: false,
         snapToStep: true
     });
	 $("#BTN_SAVE_APPOINTMENT").on("click",leads_module.SaveAppointmentInfo);
});