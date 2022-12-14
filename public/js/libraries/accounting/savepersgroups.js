/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#PG_GROUP_COMMENT' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } ); 
	 
	$("#BTN_SAVE_GROUP").on("click",personalizedgroups_module.SavePersGroupInfo);
})