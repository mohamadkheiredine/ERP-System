/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#TR_REASON_FOR_HOLIDAY' ) )
     .then( newEditor => {
        $.holiday_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	/** $('#TR_HOLIDAY_DATE_FROM').datepicker({
		 startDate :'+1d',
		 format: 'yyyy-mm-dd',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $('#TR_HOLIDAY_DATE_TO').datepicker({
		 startDate :'+1d',
		 format: 'yyyy-mm-dd',
		 todayHighlight: true,
		 orientation: "bottom left",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });*/
	 $('.input-daterange input').each(function() {
		    $(this).datepicker({
		    	 clearDates : true,
		    	 startDate :'+1d',
				 format: 'yyyy-mm-dd',
				 todayHighlight: false,
				 orientation: "bottom left",
				 templates: {
					 leftArrow: '<i class="la la-angle-left"></i>',
					 rightArrow: '<i class="la la-angle-right"></i>'
				 }});
		});
	 $("#BTN_SEND_REQUEST").on("click",holidays_module.SendHolidayRequest);
})