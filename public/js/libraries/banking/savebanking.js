/**
 * 
 */
$(function(){
	 $('select').select2();
	$("#BTN_SAVE_ACCOUNT").on("click",banking_module.SaveBankingAccountInfo);
	 ClassicEditor
     .create( document.querySelector( '#BA_ACCOUNT_COMMENT' ) )
     .then( newEditor => {
        $.account_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
})