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
	 $('#CA_ACTIVITY_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $("#BTN_SAVE_ACTIVITY").on("click",leads_module.SaveLeadActivityInfo);
})