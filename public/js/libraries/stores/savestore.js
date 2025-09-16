$(function(){
    ClassicEditor
        .create( document.querySelector( '#PS_LOCATION' ) )
        .then( newEditor => {
            $.desc_editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );
    $("button[id*=BTN_SAVE_STORE]").on("click",stores_module.SaveStoreInfo);
})
