/**
 * 
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#DN_DEBIT_NOTES' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	$("#DN_CREATION_DATE").datepicker({ 
		showButtonPanel: true,
		todayBtn : true,
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	$("#BTN_SAVE_DNOTE").on("click",debitnotes_module.SaveDebitNoteInfo);
})