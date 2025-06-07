$(function(){
    ClassicEditor
        .create( document.querySelector( '#TB_BRACKET_DESCRIPTION' ) )
        .then( newEditor => {
            $.editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );



    $("#BTN_SAVE_BRACKET").on("click",brackets_module.SaveBracketinfo);

})
