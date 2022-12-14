/**
 * 
 */

plans_module = {
		DisplayListProductionPlans : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var ps_plan_status 		= $('select[name=ps_plan_status]').val();
			$.ajax
			({
				url : base_url + "/request/productionplan/displaylist",
				data : { _token : _token , ps_plan_status : ps_plan_status },
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('.LstProdPlan').html(response.display);
					
				}
			});
		},
		DisplayListPlanProducts : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
		    var pp_id 				= $('input[name=pp_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/productionplan/displaylisproducts",
		        data : { _token : _token , pp_id : pp_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstPlanProducts').html(response.display);
	
		        }
		    });
		},
		EditProductionPlan : function(){
			var pp_id = $(this).data("pp_id");
			 var base_url = $('#BASE_URL').val();
			 window.location.href = base_url + "/production/plan/editform/" + pp_id;
		},
		DeleteProductionPlan : function(){
			var pp_id = $(this).data('pp_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
					var base_url = $('#BASE_URL').val();
					var _token = $('input[name=_token]').val();
					var str_params ={pp_id : pp_id , _token : _token};
					$.ajax
					({
						url : base_url + "/request/productionplan/deleteplaninfo",
						data : str_params,
						dataType : "Json",
						type : "POST",
						success : function(response){
							if(response.is_error == 0)
							{
								plans_module.DisplayListProductionPlans();
							}
						}
					});
				}
			});
		},
		DeleteProductPlan : function(){
			var pi_id = $(this).data('pi_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={pi_id : pi_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/productionplan/deleteproductinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  plans_module.DisplayListPlanProducts();
				              }
				            }
				        });
				}
			});
		},
		SaveQualityCheck : function(){
			return plans_module.SaveQualityCheckSubmitHandler();
		},
		SaveQualityCheckSubmitHandler : function(){
			 var QualityCheckForm = $('#FRM_QUALITY_CHECK');
	         var error3 = $('.alert-danger', QualityCheckForm);
	         var success3 = $('.alert-success', QualityCheckForm);
	        // $("#input[name=pp_estimation_time]").rules("add", { pattern: "^[0-255]:[0-255]:[0-255]$" });
	         QualityCheckForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	qc_check_label : {
	            		 required: true
	               },
	               qc_team_id : {
	            		 required: true
	               },
	               qc_product_id : {
                     required: true
                   },
                   qc_status_id : {
	                     required: false
	               }
	             },

	             messages: { // custom messages for radio buttons and checkboxes

	             },
	             errorPlacement: function (error, element) { // render error placement for each input type
	                 if (element.parent(".input-group").length > 0) {
	                     error.insertAfter(element.parent(".input-group"));
	                 } else if (element.attr("data-error-container")) {
	                     error.appendTo(element.attr("data-error-container"));
	                 } else if (element.parents('.radio-list').length > 0) {
	                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
	                 } else if (element.parents('.radio-inline').length > 0) {
	                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-list').length > 0) {
	                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-inline').length > 0) {
	                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
	                 } else {
	                     error.insertAfter(element); // for other inputs, just perform default behavior
	                 }
	             },
	             invalidHandler: function (event, validator) { //display error alert on form submit
	                 success3.hide();
	                 error3.show();
	             },
	             success: function (label) {
	                 label
	                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
	             },
	             highlight: function (element) { // hightlight error inputs
	                 $(element)
	                     .closest('.form-group').addClass('has-error'); // set error class to the control group
	             },

	             unhighlight: function (element) { // revert the change done by hightlight
	                 $(element)
	                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
	             },
	             submitHandler: function (form) {
	                success3.show();
	                error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	        var str_params = $("#FRM_QUALITY_CHECK").serialize(); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/productionplan/createqualitycheck",
	    	            data : str_params, 
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 1)
	    	              { 
	    	            	  bootbox.alert(response.error_msg);
	    	              }
	    	              else
    	            	  {
	    	            	  $('#PlanQualityCheckModel').modal('toggle');
	    	            	  plans_module.DisplayListQualityCheck();
    	            	  }
	    	            }
	    	        });
	             }

	         });
		},
		DisplayListQualityCheck : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
		    var fk_plan_id 			= $('input[name=fk_plan_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/qualitycheck/displaylist",
		        data : { _token : _token , fk_plan_id : fk_plan_id },
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		        	$('#LstQualityCheck').html(response.display);
	
		        }
		    });
		},
		SavePlanInformation : function(){
			return plans_module.SavePlanInformationSubmitHandler();
		},
		SavePlanInformationSubmitHandler : function(){
			 var ProdPlanForm = $('#FORM_SAVE_PLAN');
	         var error3 = $('.alert-danger', ProdPlanForm);
	         var success3 = $('.alert-success', ProdPlanForm);
	        // $("#input[name=pp_estimation_time]").rules("add", { pattern: "^[0-255]:[0-255]:[0-255]$" });
	         ProdPlanForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	               pp_plan_label : {
	            		 required: true
	               },
	               pp_production_manager : {
	            		 required: true
	               },
	               pp_plan_status : {
                     required: true
                   },
                   pp_customer_id : {
	                     required: false
	               },
	               pp_estimation_time : {
	            	   required : true
	               }
	             },

	             messages: { // custom messages for radio buttons and checkboxes

	             },
	             errorPlacement: function (error, element) { // render error placement for each input type
	                 if (element.parent(".input-group").length > 0) {
	                     error.insertAfter(element.parent(".input-group"));
	                 } else if (element.attr("data-error-container")) {
	                     error.appendTo(element.attr("data-error-container"));
	                 } else if (element.parents('.radio-list').length > 0) {
	                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
	                 } else if (element.parents('.radio-inline').length > 0) {
	                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-list').length > 0) {
	                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-inline').length > 0) {
	                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
	                 } else {
	                     error.insertAfter(element); // for other inputs, just perform default behavior
	                 }
	             },
	             invalidHandler: function (event, validator) { //display error alert on form submit
	                 success3.hide();
	                 error3.show();
	             },
	             success: function (label) {
	                 label
	                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
	             },
	             highlight: function (element) { // hightlight error inputs
	                 $(element)
	                     .closest('.form-group').addClass('has-error'); // set error class to the control group
	             },

	             unhighlight: function (element) { // revert the change done by hightlight
	                 $(element)
	                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
	             },
	             submitHandler: function (form) {
	                success3.show();
	                error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	        var str_params = $("#FORM_SAVE_PLAN").serialize(); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/productionplan/saveplaninfo",
	    	            data : str_params, 
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/production/planning";
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		SavePlanItem : function(){
			return plans_module.SavePlanItemSubmitHandler();
		},
		SavePlanItemSubmitHandler : function(){
			var PlanItemsForm = $('#FRM_PLAN_ITEMS');
			var error3 = $('.alert-danger', PlanItemsForm);
			var success3 = $('.alert-success', PlanItemsForm);
			
			PlanItemsForm.validate({
				errorElement: 'span', //default input error message container
				errorClass: 'help-block help-block-error', // default input error message class
				focusInvalid: false, // do not focus the last invalid input
				ignore: "", // validate all fields including form hidden input
				rules: {
					pi_item_product : {
						required: true
					},
					pi_item_quanity : {
						required: true
					}
				},
				
				messages: { // custom messages for radio buttons and checkboxes
					
				},
				errorPlacement: function (error, element) { // render error placement for each input type
					if (element.parent(".input-group").length > 0) {
						error.insertAfter(element.parent(".input-group"));
					} else if (element.attr("data-error-container")) {
						error.appendTo(element.attr("data-error-container"));
					} else if (element.parents('.radio-list').length > 0) {
						error.appendTo(element.parents('.radio-list').attr("data-error-container"));
					} else if (element.parents('.radio-inline').length > 0) {
						error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
					} else if (element.parents('.checkbox-list').length > 0) {
						error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
					} else if (element.parents('.checkbox-inline').length > 0) {
						error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
					} else {
						error.insertAfter(element); // for other inputs, just perform default behavior
					}
				},
				invalidHandler: function (event, validator) { //display error alert on form submit
					success3.hide();
					error3.show();
				},
				success: function (label) {
					label
					.closest('.form-group').removeClass('has-error'); // set success class to the control group
				},
				highlight: function (element) { // hightlight error inputs
					$(element)
					.closest('.form-group').addClass('has-error'); // set error class to the control group
				},
				
				unhighlight: function (element) { // revert the change done by hightlight
					$(element)
					.closest('.form-group').removeClass('has-error'); // set error class to the control group
				},
				submitHandler: function (form) {
					success3.show();
					error3.hide();
					var base_url = $('#BASE_URL').val();
					var str_params = $("#FRM_PLAN_ITEMS").serialize(); 
					$.ajax
					({
						url : base_url + "/request/productionplan/saveplaniteminfo",
						data : str_params, 
						method : 'post', 
						dataType : "json",
						beforeSend : function(){
						},
						success : function(response){
							if(response.is_error == 0)
							{
								plans_module.DisplayListPlanProducts();
								$('#PlanItemsModel').modal('toggle');
							}
						}
					});
				}
				
			});
		},
		SaveAssignPlanTo : function(){
			return plans_module.SaveAssignPlanToSubmitHandler();
		},
		SaveAssignPlanToSubmitHandler : function(){
			 var PlanAssignToForm = $('#FRM_PLAN_ASSIGN_TO');
	         var error3 = $('.alert-danger', PlanAssignToForm);
	         var success3 = $('.alert-success', PlanAssignToForm);

	         PlanAssignToForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 pp_assign_to : {
	            		 required: true
	               }
	             },

	             messages: { // custom messages for radio buttons and checkboxes

	             },
	             errorPlacement: function (error, element) { // render error placement for each input type
	                 if (element.parent(".input-group").length > 0) {
	                     error.insertAfter(element.parent(".input-group"));
	                 } else if (element.attr("data-error-container")) {
	                     error.appendTo(element.attr("data-error-container"));
	                 } else if (element.parents('.radio-list').length > 0) {
	                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
	                 } else if (element.parents('.radio-inline').length > 0) {
	                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-list').length > 0) {
	                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-inline').length > 0) {
	                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
	                 } else {
	                     error.insertAfter(element); // for other inputs, just perform default behavior
	                 }
	             },
	             invalidHandler: function (event, validator) { //display error alert on form submit
	                 success3.hide();
	                 error3.show();
	             },
	             success: function (label) {
	                 label
	                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
	             },
	             highlight: function (element) { // hightlight error inputs
	                 $(element)
	                     .closest('.form-group').addClass('has-error'); // set error class to the control group
	             },

	             unhighlight: function (element) { // revert the change done by hightlight
	                 $(element)
	                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
	             },
	             submitHandler: function (form) {
	                success3.show();
	                error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	        var str_params = $("#FRM_PLAN_ASSIGN_TO").serialize(); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/productionplan/assignplanto",
	    	            data : str_params, 
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              { 
	    	            	  $('#AssignPlanModel').modal('toggle');
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		PlanQualityCheck : function(){
			 $('#PlanQualityCheckModel').modal('toggle');
		},
		SavePlanApproval : function(){
			return plans_module.SavePlanApprovalSubmitHandler();
		},
		SavePlanApprovalSubmitHandler : function(){
			 var PlanApprovalForm = $('#FRM_PLAN_APPROVAL');
	         var error3 = $('.alert-danger', PlanApprovalForm);
	         var success3 = $('.alert-success', PlanApprovalForm);

	         PlanApprovalForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 pp_approve_note : {
	            		 required: true
	               }
	             },

	             messages: { // custom messages for radio buttons and checkboxes

	             },
	             errorPlacement: function (error, element) { // render error placement for each input type
	                 if (element.parent(".input-group").length > 0) {
	                     error.insertAfter(element.parent(".input-group"));
	                 } else if (element.attr("data-error-container")) {
	                     error.appendTo(element.attr("data-error-container"));
	                 } else if (element.parents('.radio-list').length > 0) {
	                     error.appendTo(element.parents('.radio-list').attr("data-error-container"));
	                 } else if (element.parents('.radio-inline').length > 0) {
	                     error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-list').length > 0) {
	                     error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
	                 } else if (element.parents('.checkbox-inline').length > 0) {
	                     error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
	                 } else {
	                     error.insertAfter(element); // for other inputs, just perform default behavior
	                 }
	             },
	             invalidHandler: function (event, validator) { //display error alert on form submit
	                 success3.hide();
	                 error3.show();
	             },
	             success: function (label) {
	                 label
	                     .closest('.form-group').removeClass('has-error'); // set success class to the control group
	             },
	             highlight: function (element) { // hightlight error inputs
	                 $(element)
	                     .closest('.form-group').addClass('has-error'); // set error class to the control group
	             },

	             unhighlight: function (element) { // revert the change done by hightlight
	                 $(element)
	                     .closest('.form-group').removeClass('has-error'); // set error class to the control group
	             },
	             submitHandler: function (form) {
	                success3.show();
	                error3.hide();
	                var base_url = $('#BASE_URL').val();
	    	        var str_params = $("#FRM_PLAN_APPROVAL").serialize(); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/productionplan/planapproval",
	    	            data : str_params, 
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              { 
	    	            	  $('#ProductionPlanApprovalModel').modal('toggle');
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		OpenAddProductPopUp : function(){
			$('#PlanItemsModel').modal('toggle');
		},
		OpenProductionPlanAssignTo : function(){
			$('#AssignPlanModel').modal('toggle');
		},
		OpenProductionPlanApproval : function(){
			$('#ProductionPlanApprovalModel').modal('toggle');
		},
		StartProductionPlan : function(){
			var base_url 	= $('#BASE_URL').val();
			var _token 		= $('input[name=_token]').val();
			var pp_id	 	= $('input[name=pp_id]').val();
	        var params = {_token : _token , pp_id : pp_id}; 
	         $.ajax
	        ({
	            url : base_url + "/request/productionplan/startproduction",
	            data : params, 
	            method : 'post', 
	            dataType : "json",
	            beforeSend : function(){
	            },
	            success : function(response){
	              if(response.is_error == 0)
	              { 
	            	 bootbox.alert(response.error_msg,function(){
	         			$("#BTN_START_PRODUCTION").css({'display':'none'});
	        			$("#BTN_PAUSE_PRODUCTION").css({'display':''});
	        			$("#BTN_BLOCK_PRODUCTION").css({'display':''});
	        			$("#BTN_QUALITY_ALERT").css({'display':''});
	        			$("#BTN_SCRAP").css({'display':''});
	        			$("#BTN_QUALITY_CHECK").css({'display':''});
	        			$("#BTN_MAINT_REQUEST").css({'display':''});
	        			$('input[name=production_run]').val('1');
	            	 });
	            	 set_timer();
	              }
	              else
            	  {
	            	  bootbox.alert(response.error_msg);
            	  }
	            }
	        });
			
		},
		CheckifProductionRunning : function(){
			 bootbox.confirm("Are you sure you want to Reload this Plan ?", function(result){ 
		        	
		        	if(result == true)
	        		{
		        		plans_module.PauseProductionPlan();
	        		}
			 });
		},
		PauseProductionPlan : function(){
			var base_url 	= $('#BASE_URL').val();
			var _token 		= $('input[name=_token]').val();
			var pp_id	 	= $('input[name=pp_id]').val();
	        var params = {_token : _token , pp_id : pp_id}; 
	         $.ajax
	        ({
	            url : base_url + "/request/productionplan/pauseproduction",
	            data : params, 
	            method : 'post', 
	            dataType : "json",
	            beforeSend : function(){
	            },
	            success : function(response){
	              if(response.is_error == 0)
	              { 
	            	 bootbox.alert(response.error_msg,function(){
	         			$("#BTN_START_PRODUCTION").css({'display':''});
	        			$("#BTN_PAUSE_PRODUCTION").css({'display':'none'});
	        			$('input[name=timer_hours]').val($('#hours').html() ); 
	        			$('input[name=timer_minutes]').val($('#minutes').html() ); 
	        			$('input[name=timer_seconds]').val($('#seconds').html() );
	        			$('input[name=production_run]').val('0');
	            	 });
	            	 stop_timer();
	              }
	              else
            	  {
	            	  bootbox.alert(response.error_msg);
            	  }
	            }
	        });
		},
		BlockProductionPlan : function(){
			var base_url 	= $('#BASE_URL').val();
			var _token 		= $('input[name=_token]').val();
			var pp_id	 	= $('input[name=pp_id]').val();
	        var params 		= {_token : _token , pp_id : pp_id};
	         
	        bootbox.confirm("Are you sure you want to Block this Plan ?", function(result){ 
	        	
	        	if(result == true)
        		{
	    	        $.ajax
	    	        ({
	    	            url : base_url + "/request/productionplan/blockproduction",
	    	            data : params, 
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              { 
	    	            	 bootbox.alert(response.error_msg,function(){
	    	         			$("#BTN_START_PRODUCTION").css({'display':''});
	    	        			$("#BTN_PAUSE_PRODUCTION").css({'display':'none'});
	    	        			$('input[name=timer_hours]').val(); 
	    	        			$('input[name=timer_minutes]').val($('#minutes').html() ); 
	    	        			$('input[name=timer_seconds]').val($('#seconds').html() );
	    	        			$('#hours').html('00');
	    	        			$('#minutes').html('00');
	    	        			$('#seconds').html('00');
	    	        			$('input[name=production_run]').val('0');
	    	            	 });
	    	            	 stop_timer();
	    	              }
	    	              else
	                	  {
	    	            	  bootbox.alert(response.error_msg);
	                	  }
	    	              
	    	            }
	    	        });
        		}
	        	
	        });

		},
		OpenQualityCheckProduction : function(){
			let base_url 	= $('#BASE_URL').val();
			let _token 		= $('input[name=_token]').val();
			let qc_id	 	= $('input[name=pp_id]').val();
			let params = { qc_id : qc_id , _token : _token };
			 $.ajax
 	        ({
 	            url : base_url + "/request/plan/editqualitycheck",
 	            data : params, 
 	            method : 'post', 
 	            dataType : "json",
 	            beforeSend : function(){
 	            },
 	            success : function(response){
 	              if(response.is_error == 0)
 	              { 
 	            	 bootbox.alert(response.error_msg,function(){
 	         			$("#BTN_START_PRODUCTION").css({'display':''});
 	        			$("#BTN_PAUSE_PRODUCTION").css({'display':'none'});
 	        			$('input[name=timer_hours]').val(); 
 	        			$('input[name=timer_minutes]').val($('#minutes').html() ); 
 	        			$('input[name=timer_seconds]').val($('#seconds').html() );
 	        			$('#hours').html('00');
 	        			$('#minutes').html('00');
 	        			$('#seconds').html('00');
 	        			$('input[name=production_run]').val('0');
 	            	 });
 	            	 stop_timer();
 	              }
 	              else
             	  {
 	            	  bootbox.alert(response.error_msg);
             	  }
 	              
 	            }
 	        });
			
		}
};