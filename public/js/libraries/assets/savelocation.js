$(function(){
    ClassicEditor
        .create( document.querySelector( '#IL_DESCRIPTION' ) )
        .then( newEditor => {
            $.il_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    $('#BTN_SAVE_LOCATION').on('click',alocations_module.SaveALocationInfo);
});
