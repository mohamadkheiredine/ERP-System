/**
 *
 */


$(function(){
	 ClassicEditor
     .create( document.querySelector( '#W_WAREHOUSE_DESCRIPTION' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	warehouses_module.SaveWareHouse();
	$("#BACK_FORM").on("click",warehouses_module.CancelForm);
	$("#BTN_ALLOW_VEHICULE").on("click",function(){
		$("#VEHICULES option:selected").each(function(){
			$("#ALLOWED_VEHICULES").append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
			$(this).remove();
		})
	});
	$("#BTN_REMOVE_VEHICULE").on("click",function(){
		$("#ALLOWED_VEHICULES option:selected").each(function(){
			$("#VEHICULES").append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
			$(this).remove();
		})
	});
	$('select').select2();
	  $('[data-switch=true]').bootstrapSwitch();
});