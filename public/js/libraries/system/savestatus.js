$(function(){
    ClassicEditor
        .create( document.querySelector( '#SS_STATUS_DESCRIPTION' ) )
        .then( newEditor => {
            $.status_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    $('#BTN_SAVE_STATUS').on('click',sysstatus_module.SaveSystemStatusInfo);
});
