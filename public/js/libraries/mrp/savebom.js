/**
 *
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#BM_BOM_NOTES' ) )
     .then( newEditor => {
        $.editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	$('select').select2();
	$('#BTN_SAVE_BOM').on('click',bom_module.SaveBOMInfo);
	$('#BM_PRODUCT_ID').on('change',bom_module.DisplayProductInfo);
	$('#BM_ITEM_PRODUCT').on('change',bom_module.DisplayItemProductInfo);
	$('#BTN_INSERT_ITEM').on('click',bom_module.SaveBOMItems);
	$('#BTN_ADD_PRODUCT').on('click',bom_module.AddBOMProduct);
	$('#BM_QUANTITY_TYPE').on('change',bom_module.DisplayUnitsDropdown);
	$('input[name=bm_item_quanity]').on('keypress',bom_module.CalculateTotalPrice);
	var bm_id = $('input[name=bm_id]').val();
	if(bm_id != null)
	{
		bom_module.DisplayProductInfo();
	}
    bom_module.DisplayListBOMItems();

})
