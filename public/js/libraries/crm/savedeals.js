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
	 $('select').select2();
	 $('#AD_CLOSING_DATE').datepicker({
		 startDate :'+1d',
		 todayHighlight: true,
		 orientation: "bottom left",
		 format : "yyyy-mm-dd",
		 templates: {
			 leftArrow: '<i class="la la-angle-left"></i>',
			 rightArrow: '<i class="la la-angle-right"></i>'
		 }
	 });
	 $("#BTN_SAVE_DEALS").on('click',deals_module.SaveDealsInfo);
})