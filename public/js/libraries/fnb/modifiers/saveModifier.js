$(function() {
    ClassicEditor
        .create( document.querySelector( '#M_MODIFIER_DESCRIPTION' ) )
        .then( newEditor => {
            $.editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );
    $("button[id*=BTN_SAVE_MODIFIER]").on("click",modifiers_module.SaveModifierInfo);
    $("button[name=back_form]").on("click", modifiers_module.backToPreviousPage);
})
