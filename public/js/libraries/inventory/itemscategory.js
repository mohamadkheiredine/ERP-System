/**
 * 
 */
$(function(){
	itemscategory_module.DisplayListItems();
	$("button[name=btn_new_product]").on("click",itemscategory_module.AddNewCategory)
	$("a[id*=EDIT_PRODUCT_]").on("click",itemscategory_module.EditProductPage)
})