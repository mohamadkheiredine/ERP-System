/**
 * 
 */

suppliers_module = {
		DisplayListSuppliers : function(){
			var base_url 			= $('input[name=base_url]').val(); 
			var _token 			= $('input[name=_token]').val(); 
			var supplier_category	 	= $('select[name=supplier_category]').val(); 
			var supplier_status	 	= $('select[name=supplier_status]').val(); 
		    var params = { _token : _token , supplier_category : supplier_category , supplier_status : supplier_status };
		    $.ajax
	        ({
	            url : base_url + "/request/srm/displaylistsuppliers",
	            data : params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	$('#LstSuppliers').html(response.display);
	            	 $('.group-checkable').change(function() {
                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                        var checked = $(this).prop("checked");
                        $(set).each(function() {
                            $(this).prop("checked", checked);
                        });
                        $.uniform.update(set);
                    });
	            }
	        });
		},
		EditSupplierInfo : function(){
			var ss_id 		= $(this).data("ss_id");
			var base_url 	= $('input[name=base_url]').val();
			window.location.href = base_url + "/srm/suppliers/edit/" + ss_id;
		},
		DeleteSupplierInfo : function(){
			 var ss_id = $(this).data('ss_id'); 
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={ss_id : ss_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/srm/deletesupplierinfo",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  $.suppliers_datatable.destroy();
					            	  suppliers_module.DisplayListSuppliers();
					              }
					            }
					        });
					}
				});
		},
		AddNewAccount : function(){
			var parent_account 	= $('select[name=aa_parent_account]').val();
			var account_label 	= $('input[name=aa_account_label]').val();
			var dropdown_name 	= $('input[name=dropdown_name]').val();
				
			var _token 			= $('input[name=_token]').val();
		     var base_url = $('#BASE_URL').val();
			var params = {parent_account : parent_account , account_label : account_label , _token : _token};
			$.ajax
	        ({
	            url : base_url + "/request/suppliers/saveaccaccounting",
	            data : params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  let $dropdown = $('select.SrmAccount');
	            	  $dropdown.select2('destroy');
	            	  $dropdown.append("<option selected='selected' value='" +  response.aa_id + "'>" + response.account_ref + " - " + response.accounting_label + "</option>");
	            	  $dropdown.select2();
	            	  $("#AccountAccounting").modal('toggle');
	              }
	              else
	        	  {
	            	  bootbox.alert(response.error_msg);
	        	  }
	            }
	        });
			
		},
		OpenAddNewAccount : function(){
			var dropdown_name 	= $(this).data('dropdown_name');
			$('input[name=dropdown_name]').val(dropdown_name);
			// $("select[name=aa_parent_account]").select2();
			$("#AccountAccounting").modal('toggle');
		},
		SaveSupplierInfo : function(){
			suppliers_module.SaveSupplierSubmitHandler();
		},
		SaveSupplierSubmitHandler : function(){
			 var SaveSupplierForm = $('#FORM_SAVE_SUPPLIER');
	         var error3 = $('.alert-danger', SaveSupplierForm);
	         var success3 = $('.alert-success', SaveSupplierForm);

	         SaveSupplierForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 ss_purchase_account_id : {
	                     required: true
	                 },
	                 ss_sale_account_id : {
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
	    	       
	                var FormDataFields = $("form[id=FORM_SAVE_SUPPLIER]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        $.each($("input[type=file]"), function(i, obj) {
	    	                var name = $(this).attr('name');
	    	                $.each(obj.files,function(j,file){
	    	                        data.append(name, file);
	    	                })
	    	        });

	    	        FormDataFields.find('input,select').each(function(){
	    	                data.append($(this).attr('name'), $(this).val() );
	    	        });
	    	         
	    	        const ss_supplier_description = $.desc_editor.getData();
	    	        data.append("ss_supplier_description", ss_supplier_description ); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/srm/savesupplierinfo",
	    	            data : data,
	    	            async: false,
	    	            cache: false,
	    	            method : 'post',
	    	            contentType: false,
	    	            processData: false,
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                 window.location.href = base_url + "/srm/suppliers";
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		}
};