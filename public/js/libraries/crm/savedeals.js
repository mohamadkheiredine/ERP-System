/**
 * 
 */
$(function(){
	$.desc_editor;
	$.nextstep_editor;
	 ClassicEditor
     .create( document.querySelector( '#AD_DEAL_DESCRIPTION' ) )
     .then( newEditor => {
        $.desc_editor = newEditor;
    }).catch( error => {
         console.error( error );
    });
	 
	 ClassicEditor
     .create( document.querySelector( '#AD_NEXT_STEP' ) )
     .then( newEditor => {
        $.nextstep_editor = newEditor;
    }).catch( error => {
         console.error( error );
     });
     	 new tempusDominus.TempusDominus(document.getElementById('AD_CLOSING_DATE'),{
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
	 $("#BTN_SAVE_DEALS").on('click',deals_module.SaveDealsInfo);
	 $("#BTN_ADD_PRODUCT").on('click',function() {
             $('#ProductsDealModel').modal('toggle');
         });
})