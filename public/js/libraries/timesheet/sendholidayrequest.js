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
     
       new tempusDominus.TempusDominus(document.getElementById('TR_HOLIDAY_DATE_FROM'),{
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
                            format : "MM/dd/yyyy"

                    }
           });
            new tempusDominus.TempusDominus(document.getElementById('TR_HOLIDAY_DATE_TO'),{
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
                            format : "MM/dd/yyyy"

                    }
           });
	 $("#BTN_SEND_REQUEST").on("click",holidays_module.SendHolidayRequest);
})