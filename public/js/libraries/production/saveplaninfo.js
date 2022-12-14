/**
 * 
 */
$(function(){
	ClassicEditor
    .create( document.querySelector( '#PP_PLAN_DESCRIPTION' ) )
    .then( newEditor => {
       $.pp_editor = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	$('#PP_PREPARE_DATE').datepicker({
		startDate :'+1d',
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	$('#PP_PREPARE_DATE').on('changeDate', function(e) {
		$('#PP_END_DATE').datepicker({
			startDate :e.date,
			todayHighlight: true,
			orientation: "bottom left",
			format : "yyyy-mm-dd",
			templates: {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>'
			}
		});
	});

	$('#PP_START_DATE').datepicker({
		startDate :'+1d',
		todayHighlight: true,
		orientation: "bottom left",
		format : "yyyy-mm-dd",
		templates: {
			leftArrow: '<i class="la la-angle-left"></i>',
			rightArrow: '<i class="la la-angle-right"></i>'
		}
	});
	
	$('#PP_START_DATE').on('changeDate', function(e) {
		 $('#PP_FINISH_DATE').datepicker({
			 startDate :e.date,
			 todayHighlight: true,
			 orientation: "bottom left",
			 format : "yyyy-mm-dd",
			 templates: {
				 leftArrow: '<i class="la la-angle-left"></i>',
				 rightArrow: '<i class="la la-angle-right"></i>'
			 }
		 });
	});
	$("#BTN_SAVE_PLAN").on('click',plans_module.SavePlanInformation);
	$('select').select2();
	
	//check if edit mode
	let pp_id = $('input[name=pp_id]').val();
	if(pp_id != null)
	{
		plans_module.DisplayListPlanProducts();
		plans_module.DisplayListQualityCheck();
		$("#LstPlanProducts").on('click',"a[id*=DELETE_ITEM_]",plans_module.DeleteProductPlan);
		$("#BTN_ADD_PRODUCT").on('click',plans_module.OpenAddProductPopUp);
		$("#BTN_INSERT_ITEM").on('click',plans_module.SavePlanItem);
		$("#PlanApproval").on('click',plans_module.OpenProductionPlanApproval);
		$("#AssignToUser").on('click',plans_module.OpenProductionPlanAssignTo);
		$("#BTN_ASSIGN_USER").on('click',plans_module.SaveAssignPlanTo);
		$("#BTN_APPROVAL_USER").on('click',plans_module.SavePlanApproval);
		$("#BTN_START_PRODUCTION").on('click',plans_module.StartProductionPlan);
		$("#BTN_PAUSE_PRODUCTION").on('click',plans_module.PauseProductionPlan);
		$("#BTN_BLOCK_PRODUCTION").on('click',plans_module.BlockProductionPlan);
		$("#BTN_QUALITY_CHECK").on('click',plans_module.PlanQualityCheck);
		$("#BTN_SAVE_CHECK").on('click',plans_module.SaveQualityCheck);
		$("#LstQualityCheck").on('click',"a[id*=EDIT_CHECK_]",plans_module.OpenQualityCheckProduction);
		window.onbeforeunload = function (event) {
			if($('input[name=production_run]').val() == '1')
			{
				var message = 'Important: Please click on \'Save\' button to leave this page.';
			    if (typeof event == 'undefined') {
			        event = window.event;
			    }
			    if (event) {  
			        event.returnValue = message;
			    }
			    return message;
			}
		};
		
		
		
	}
});
