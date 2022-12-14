/**
 * 
 */

operations_module = {
		DisplayListOperations : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
			var account_id	 		= $('select[id=FK_ACCOUNT_ID]').val();
		    var warehouse_id	 	= $('select[id=FK_WAREHOUSE_ID]').val();
		    var params = { _token : _token , account_id : account_id , warehouse_id : warehouse_id};
		    $.ajax
	        ({
	            url : base_url + "/request/shipment/operations/displaylist",
	            data : params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	$('#LstShipmentOperations').html(response.display);
	            	var datatable = $('.m_datatable').mDatatable({

	        			// layout definition
	        			layout: {
	        				theme: 'default', // datatable theme
	        				class: '', // custom wrapper class
	        				scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
	        				// height: 450, // datatable's body's fixed height
	        				footer: false // display/hide footer
	        			},

	        			// column sorting
	        			sortable: true,

	        			pagination: true,

	        			search: {
	        				input: $('#generalSearch')
	        			},

	        			// inline and bactch editing(cooming soon)
	        			// editable: false,
	        		});
	            	$("a[id*=EDIT_SHIPOP_]").on("click",operations_module.EditOperationInfo);
	            	$("a[id*=DELETE_SHIPOP_]").on("click",operations_module.DeleteOperationInfo);
	            }
	        });
		},
		SaveOperationInfo : function(){
			return operations_module.SaveOperationInfoSubmitHandler();
		},
		SaveOperationInfoSubmitHandler : function(){
			 var SaveOperationForm = $('#FORM_SAVE_OPERATION');
	         var error3 = $('.alert-danger', SaveOperationForm);
	         var success3 = $('.alert-success', SaveOperationForm);

	         SaveOperationForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 so_operation_reference : {
	                     required: true
	                 },
	                 so_operation_label : {
	                     required: true
	                 },
	                 so_operation_type : {
	                     required: true
	                 },
	                 so_operation_status : {
	                	 required: true
	                 },
	                 so_operation_date : {
	                	 required: true
	                 },
	                 so_warehouse_source : {
	                	 required: true
	                 }
	                 /**,
	                 so_operation_time : {
	                     required: true
	                 }*/
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
	    	        var str_params = $("#FORM_SAVE_OPERATION").serialize();
	    	        const so_operation_description  = $.editor.getData();
	    	        str_params = str_params + "&so_operation_description=" + so_operation_description;
	    	        
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/shipment/operations/saveinfo",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/shipments/shipmentoperations";
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		EditOperationInfo : function(){
			var so_id = $(this).data('so_id');
			var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/shipments/shipmentoperations/editform/" + so_id;
		},
		DeleteOperationInfo : function(){
			 var so_id = $(this).data('so_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var params ={so_id : so_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/shipment/operations/deleteinfo",
					            data : params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  operations_module.DisplayListOperations();
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
		DisplayOperationTypeFields : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
			var operation_type	 	= $('select[id=SO_OPERATION_TYPE]').val();
			var so_id	 			= $('input[name=so_id]').val();
			 var params = { _token : _token , operation_type : operation_type , so_id : so_id };
			    $.ajax
		        ({
		            url : base_url + "/request/shipment/operations/displayformtype",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	 $("#OPERATION_INFO").html(response.display);
		            	 $('#SO_OPERATION_VEHICULE').select2({  placeholder: "Select Vehicules" });
		            	 operations_module.DisplayListOperationProducts();
		            }
		        });
		},
		DisplayListOperationProducts : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
			var warehouse_source	= $('#SO_WAREHOUSE_SOURCE').val();
			var so_id				= $('input[name=so_id]').val();
			var operation_type	 	= $('select[id=SO_OPERATION_TYPE]').val();
			 var params = { _token : _token , operation_type : operation_type , warehouse_source : warehouse_source , so_id : so_id };
			    $.ajax
		        ({
		            url : base_url + "/request/shipment/operations/displayproducts",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	$("#ListProducts").html(response.display);
		            }
		        });
		},
		AddNewProductRow : function(){ 
			var $lastrow = $("#ProductsRow tbody tr:last").clone();
			$lastrow.find("select").val("");
			$lastrow.find("input[type=text]").val("");
			$lastrow.find(".deleteRow").css("display","");
			$("#ProductsRow tbody").append($lastrow);
		},
		DeleteProductRow : function(){
			$(this).parents('tr').fadeOut("fast",function(){
				$(this).remove();
			})
		},
		GetVehiculeDropdown : function(){
			var w_id = $(this).val();
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
			 var params = { _token : _token ,  w_id : w_id };
			  $.ajax
		        ({
		            url : base_url + "/request/general/getdropdown/warehouse_vehicules",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	$(".VehiculeDropdown").html(response.display);
		            	 $('#SO_OPERATION_VEHICULE').select2({  placeholder: "Select Vehicules" });
		            }
		        });
		}
};