/**
 * 
 */

$.editor
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#SC_CATEGORY_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	  $("select").select2();
	 $("#BTN_SAVE_CATEGORY").on('click',suppliercategories_module.SaveSupplierCategoryInfo);
})