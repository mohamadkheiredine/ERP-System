$.editor
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#UT_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } ); 
	 $("#BTN_SAVE_TEAM").on('click',teams_module.SaveTeamInfo);
})