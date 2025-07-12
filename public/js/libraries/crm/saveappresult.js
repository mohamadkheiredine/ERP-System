$(function(){
    ClassicEditor
        .create( document.querySelector( '#AR_APP_DESCRIPTION' ) )
        .then( newEditor => {
            $.res_desc = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

    $('#BTN_SAVE_RESULT').on('click',appresult_module.SaveAppResultInfo);
});
