/**
 * 
 */

$(function(){
	suppliercategories_module.displayListSupplierCategories();
	$('#generalSearch').on('keyup',suppliercategories_module.displayListSupplierCategories);
	$('#LstSupplierCategories').on('click',"a[id*=EDIT_CATEGORY_]",suppliercategories_module.EditCategoryInfo);
	$('#LstSupplierCategories').on('click',"a[id*=DELETE_CATEGORY_]",suppliercategories_module.DeleteCategoriesData);
})