/**
 * 
 */
suppliercontracts_module = {
	DisplayListConracts : function(){
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val();
	    var ss_supplier = $('select[name=ss_supplier]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/srm/displaylistsupcontracts",
	        data : { _token : _token , ss_supplier : ss_supplier },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstSupplierContracts').html(response.display);
				$.ba_datatable = $('.m_datatable').mDatatable({
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
        			columns : [
        				{
        					field: 'Id',
        					type: 'number',
        					width: 4
        				},
        				{
        					field: 'Contract Title',
        					type: 'text'
        				},
        				{
        					field: 'Contract Date',
        					type: 'text'
        				},
        				{
        					field: 'Contract Price',
        					type: 'number'
        				},
        				{
        					field: 'Contract Currency',
        					type: 'text'
        				}
        			]
					
					// inline and bactch editing(cooming soon)
					// editable: false,
				});
				
				$("a[id*=EDIT_CONTRACT_]").on('click',suppliercontracts_module.EditContractInfo);
				$("a[id*=DELETE_CONTRACT_]").on('click',suppliercontracts_module.DeleteContractData);
	        }
	    });
	},
	EditContractInfo : function(){
		var sc_id = $(this).data('sc_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/srm/suppliercontracts/editform/" + sc_id;
	},
	DeleteContractData : function(){
		var sc_id = $(this).data('sc_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={sc_id : sc_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/srm/deletecontractinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  $.sc_datatable.destroy();
				            	  suppliercontracts_module.DisplayListConracts();
				              }
				            }
				        });
				}
			});
	},
	SaveSupplierContractInfo : function(){
		return suppliercontracts_module.SaveSupplierContractSubmitHandler();
	},
	SaveSupplierContractSubmitHandler : function(){
		 var ContractForm = $('#FORM_SAVE_CONTRACT');
         var error3 = $('.alert-danger', ContractForm);
         var success3 = $('.alert-success', ContractForm);

         ContractForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 sc_code : {
            		 required: true
            	 },
            	 sc_contract_title : {
            		 required: true
            	 },
            	 sc_contract_date : {
            		 required: true
            	 },
            	 sc_contract_delivery_date : {
            		 required: true
            	 },
            	 fk_user_owner : {
            		 required: true
            	 },
            	 fk_supplier_id : {
            		 required: true
            	 },
            	 sc_payment_type : {
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
    	       // var _token = $('input[name=_token]').val();
    	        const sc_contract_description  = $.desc_editor.getData();
    	         
    	        var str_params = $("#FORM_SAVE_CONTRACT").serialize();
    	        str_params = str_params + "&sc_contract_description=" + sc_contract_description;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/srm/savecontractinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/srm/suppliercontracts";
    	              }
    	            }
    	        });
    	         
    	         ///^([A-Z]{2}[ \-]?[0-9]{2})(?=(?:[ \-]?[A-Z0-9]){9,30}$)((?:[ \-]?[A-Z0-9]{3,5}){2,7})([ \-]?[A-Z0-9]{1,3})?$/
             }

         });
	},
	InsertRawProductToContract : function(){
		var base_url 				= $('input[name=base_url]').val();
		var _token 					= $('input[name=_token]').val(); 
		var sc_id 					= $('input[name=sc_id]').val();
		var product_id 				= $('#FK_PRODUCT_ID').val();
		if(product_id == '')
		{
			bootbox.alert("Please Select A Raw Product");
			return false;
		}
		
		var sr_product_quantity 	= $('#SR_PRODUCT_QUANITY').val();
		
		if( sr_product_quantity == 0 )
		{
			bootbox.alert("Please Add a Quantity For Selected Product");
			return false;
		}
		
		var params = { _token : _token , sc_id : sc_id , product_id : product_id , sr_product_quantity : sr_product_quantity };
		 $.ajax
		    ({
		        url : base_url + "/request/srm/addproductcontract",
		        data : params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$.product_datatable.destroy();
		        	suppliercontracts_module.DisplayContractProducts();
		        }
		    });
	},
	DisplayContractProducts : function(){
		var sc_id = $('input[name=sc_id]').val();
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val(); 
	    $.ajax
	    ({
	        url : base_url + "/request/srm/displaylistcontractproducts",
	        data : { _token : _token , sc_id : sc_id },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstProducts').html(response.display);
				$.product_datatable = $('#LstProducts').mDatatable({
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
				$("a[id*=DELETE_CNT_SUPP_]").on('click',suppliercontracts_module.DeleteProductContract);
	        }
	    });
	},
	DeleteProductContract : function(){
		var sc_id 		= $('input[name=sc_id]').val();
		var base_url 	= $('input[name=base_url]').val();
		var _token 		= $('input[name=_token]').val(); 
		var sp_id 		= $(this).data("sp_id");
		
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
				var params = { sp_id : sp_id , sc_id : sc_id };
				 $.ajax
			    ({
			        url : base_url + "/request/srm/deletecontractproducts",
			        data : { _token : _token , sc_id : sc_id },
		            method : 'post',
		            dataType : "json",
		            beforeSend : function(){
		            },
			        success : function(response){
			        	$.product_datatable.destroy();
			        	suppliercontracts_module.DisplayContractProducts();
			        }
			    });
			}
		});
		
		
	}
};