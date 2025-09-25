$(function(){
    ClassicEditor
        .create( document.querySelector( '#PT_DESCRIPTION' ) )
        .then( newEditor => {
            $.desc_editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );
    $("button[id*=BTN_SAVE_TERMINAL]").on("click",terminals_module.SaveTerminalInfo);
})
