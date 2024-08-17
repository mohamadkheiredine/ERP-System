$(function(){
	ClassicEditor
    .create( document.querySelector( '#CCA_DESCRIPTION' ) )
    .then( newEditor => {
       $.category_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	
	$('#BTN_SAVE_CATEGORY').on('click',cccategories_module.SaveCostCenterCategoriesInfo); 
});