
$(function(){
	$("#BTN_SAVE_ROLE").on('click',roles_module.SaveRoleInfo); 
	
	
	 ClassicEditor
     .create( document.querySelector( '#ROLE_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	
	$('.group-checkable').change(function() {
        var set = $(this).parents('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
        var checked = $(this).prop("checked");
        $(set).each(function() {
            $(this).prop("checked", checked);
        });
       // $.uniform.update(set);
    });
})