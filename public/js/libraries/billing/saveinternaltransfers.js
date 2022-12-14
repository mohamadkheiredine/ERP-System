/**
 * 
 */

$(function(){
	 ClassicEditor
     .create( document.querySelector( '#IN_TRANSFER_NOTES' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	 $('select').select2();
	$("#IN_TRANSFER_DATE").datepicker({ 
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
	$("#BTN_SAVE_NOTE").on("click",inttransfers_module.SaveTransferNoteInfo);
})