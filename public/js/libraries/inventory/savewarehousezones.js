$(function(){
    ClassicEditor
        .create( document.querySelector( '#WZ_ZONE_DESCRIPTION' ) )
        .then( newEditor => {
            $.zone_editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );
    $("#BTN_SAVE_ZONE").on('click',zones_module.SaveWarehouseZonesInfo);
})
