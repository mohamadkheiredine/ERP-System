$(function(){
	cccategories_module.DisplayListCostCenterCategories();
	$("#generalSearch").on('keyup',cccategories_module.DisplayListCostCenterCategories);

	$('#LstCostCategories').on('click',"a[id*=EDIT_CATEGORY_]",cccategories_module.EditCategoryInfo);
	$('#LstCostCategories').on('click',"a[id*=DELETE_CATEGORY_]",cccategories_module.DeleteCategoryData);
})