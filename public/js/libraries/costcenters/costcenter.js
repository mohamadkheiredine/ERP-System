$(function(){
	costcenter_module.DisplayListCostCenter();
	$("#generalSearch").on('keyup',costcenter_module.DisplayListCostCenter);
	$("select").on('change',costcenter_module.DisplayListCostCenter);

	$('#LstCostCenters').on('click',"a[id*=EDIT_COSTCENTER_]",costcenter_module.EditCostCenterInfo);
	$('#LstCostCenters').on('click',"a[id*=DELETE_COSTCENTER_]",costcenter_module.DeleteCostcenterData);
})