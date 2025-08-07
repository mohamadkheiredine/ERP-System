$(function(){
    ClassicEditor
        .create( document.querySelector( '#EC_DESCRIPTION' ) )
        .then( newEditor => {
            $.category_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    $('#BTN_SAVE_CATEGORY').on('click',expensecategories_module.SaveExpenseCategoriesInfo);
});
