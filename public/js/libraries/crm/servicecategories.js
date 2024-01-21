/**
 * 
 */

$(function(){
	servicecategories_module.displayListServiceCategories(); 
	$('#LstServiceCategories').on('click',"a[id*=EDIT_CATEGORY_]",servicecategories_module.EditCategoryInfo);
	$('#LstServiceCategories').on('click',"a[id*=DELETE_CATEGORY_]",servicecategories_module.DeleteServiceCategoriesData);
})