$(function(){
    ClassicEditor
        .create( document.querySelector( '#AC_DESCRIPTION' ) )
        .then( newEditor => {
            $.il_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    $('#BTN_SAVE_CATEGORY').on('click',acategories_module.SaveACategoryInfo);
});
