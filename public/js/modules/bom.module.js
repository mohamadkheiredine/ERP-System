/**
 * 
 */

bom_module ={
		DisplayListBOM : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var bo_product = $('select[name=bo_product]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/bom/displaylistbom",
		        data : { _token : _token , bo_product : bo_product },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstBom').html(response.display);
		        }
		    });
		},
		DisplayListBOMItems : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var bm_id		 = $('input[name=bm_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/bom/displaylistitems",
		        data : { _token : _token , bm_id : bm_id },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#TableBOMProducts').html(response.display);
		        	$('a[id*=DELETE_ITEM_]').on('click',bom_module.DeleteBOMItem);
		        }
		    });
		},
		EditBomInfo : function(){
			var bm_id = $(this).data('bm_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/mrp/billofmaterial/editform/" + bm_id;
		},
		DeleteBOMData : function(){
			 var bm_id = $(this).data('bm_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={bm_id : bm_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/bom/deletebominfo",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  $.bo_datatable.destroy();
					            	  bom_module.DisplayListBOM();
					              }
					            }
					        });
					}
				});
		},
		DeleteBOMItem : function(){
			var bi_id = $(this).data('bi_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={bi_id : bi_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/bom/deletebomitem",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {  
				            	  var bm_variable_cost = $('input[name=bm_variable_cost]').val();
				            	  bm_variable_cost = parseFloat(bm_variable_cost) - parseFloat(response.price);
				            	  $('input[name=bm_variable_cost]').val(bm_variable_cost);
				            	  bom_module.DisplayListBOMItems();
				              }
				            }
				        });
				}
			});
		},
		DisplayProductInfo : function(){
			var product_id = $('select[name=bm_product_id]').val();
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/bom/displayproductinfo",
		        data : { _token : _token , product_id : product_id },
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
		        		$('.BareCode').html(response.product_code);
		        		$('.ProductLabel').html(response.product_label); 
		        		$('input[name=bm_fixed_cost]').val(response.product_selling_price);
		        		$('select[name=bm_currency_id]').val(response.product_currency);
	        		}
		        }
		    });
		},
		DisplayItemProductInfo : function(){
			var product_id = $(this).val();
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/bom/displayproductinfo",
		        data : { _token : _token , product_id : product_id },
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
		        		$('select[name=bm_quantity_type]').val(response.product_unit_type); 
		        		$('input[name=bm_item_price]').val(response.product_selling_price);
		        		$('input[name=ini_item_price]').val(response.product_selling_price);
		        		bom_module.DisplayUnitsDropdown();
	        		}
		        }
		    });
		},
		AddBOMProduct : function(){ // add items to save it into the 
			$("#BOMItemsModel").modal('toggle');
		},
		SaveBOMItems : function(){ // save bom itmes in the database
			bom_module.SaveBOMItemsSubmitHandler();
		},
		SaveBOMItemsSubmitHandler : function(){
			 var FrmBOMItems = $('#FRM_BOM_ITEMS');
	         var error3 = $('.alert-danger', FrmBOMItems);
	         var success3 = $('.alert-success', FrmBOMItems);

	         FrmBOMItems.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 bm_raw_material : {
	            		 required: true
	            	 },
	            	 bm_quantity_type : {
	            		 required: true
	            	 },
	            	 bm_item_quanity : {
	            		 required: true,
	            		 min : 0.1
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
	    	       
	                var params = $("form[id=FRM_BOM_ITEMS]").serialize();
	                var bm_currency_id = $('select[name=bm_currency_id]').val();
	                var bm_id = $('input[name=bm_id]').val();
	                var _token = $('input[name=_token]').val();
	                params = params + "&bm_id=" + bm_id + "&bm_currency_id=" + bm_currency_id + "&_token=" + _token; 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/bom/savebomitems",
	    	            data : params, 
	    	            method : 'post', 
	    	            dataType : "json", 
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  bootbox.alert(response.error_msg,function(){
		    	            	  var variable_cost = $('#BM_VARIABLE_COST').val();
		    	            	  $('#BM_VARIABLE_COST').val(parseFloat(variable_cost) + parseFloat(response.bm_item_price));
		    	            	  bom_module.DisplayListBOMItems();
		    	            	  $("#BOMItemsModel").modal('toggle');
	    	            	  });

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
		CalculateTotalPrice : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    
		    var bm_quantity_type 	= $("select[name=bm_quantity_type]").val();
		    var bm_item_quanity 	= $("input[name=bm_item_quanity]").val(); 
		    var bm_system_units 	= $("select[name=bm_system_units]").val(); 
		    var product_id 			= $("select[id=BM_ITEM_PRODUCT]").val();

		    var params = { _token : _token , bm_quantity_type : bm_quantity_type , bm_item_quanity : bm_item_quanity , product_id : product_id , bm_system_units : bm_system_units};
		    //let new_price = ( parseFloat(bm_item_quanity) * parseFloat(bm_item_price) ) /  parseFloat(ini_item_weight);
		    //$("input[name=bm_item_price]").val(new_price);
		    $.ajax
		    ({
		        url : base_url + "/request/mrp/calculatetotalprice",
		        data : params,
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
		        		$("input[name=bm_item_price]").val(response.new_price);
	        		}
		        }
		    });
		    
		},
		DisplayUnitsDropdown : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
		    var bm_quantity_type 	= $('select[name=bm_quantity_type]').val();
		    
		    var params = { _token : _token , bm_quantity_type : bm_quantity_type };
		    $.ajax
		    ({
		        url : base_url + "/request/general/getdropdown/systemunits",
		        data : params,
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
		        		$('.UnitsDropDown').html(response.dropdown); 
	        		}
		        }
		    });
		    
		},
		SaveBOMInfo : function(){
			bom_module.SaveBOMInfoSubmitHandler();
		},
		SaveBOMInfoSubmitHandler : function(){
			 var SaveBOMInfo = $('#FORM_SAVE_BOM');
	         var error3 = $('.alert-danger', SaveBOMInfo);
	         var success3 = $('.alert-success', SaveBOMInfo);

	         SaveBOMInfo.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 bm_label : {
	            		 required: true
	            	 },
	            	 bm_product_id : {
	            		 required: true
	            	 },
	            	 bm_fixed_cost : {
	                     required: true
	                 },
	                 bm_variable_cost : {
	                     required: true
	                 },
	                 bm_currency_id : {
	                     required: true
	                 }, 
	                 bm_bom_type : {
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
	    	       
	                var params = $("form[id=FORM_SAVE_BOM]").serialize();


	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/bom/savebominfo",
	    	            data : params, 
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/mrp/billofmaterial";
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		}
};