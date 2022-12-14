/**
 * 
 */

clients_module = {
		DisplayListClients : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
			var account_category	 	= $('select[name=account_category]').val(); 
		    var params = { _token : _token , account_category : account_category };
		    $.ajax
	        ({
	            url : base_url + "/request/clients/displaylist",
	            data : params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	$('#LstClients').html(response.display);
	            	$.clients_datatable = $('.m_datatable').mDatatable({

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
	        					field: 'Client name',
	        					type: 'text'
	        				},
	        				{
	        					field: 'Company',
	        					type: 'text'
	        				},
	        				{
	        					field: 'Mobile',
	        					type: 'text'
	        				},
	        				{
	        					field: 'Email',
	        					type: 'text'
	        				}
	        			]

	        			// inline and bactch editing(cooming soon)
	        			// editable: false,
	        		});
	            	 $('.group-checkable').change(function() {
	                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
	                        var checked = $(this).prop("checked");
	                        $(set).each(function() {
	                            $(this).prop("checked", checked);
	                        });
	                        $.uniform.update(set);
	                    });
	            	$("a[id*=EDIT_ACCOUNT_]").on("click",clients_module.EditAccountInfo);
	            	$("a[id*=DELETE_ACCOUNT_]").on("click",clients_module.DeleteAccountInfo);
	            }
	        });
		},
		EditAccountInfo : function(){
			var ca_id 		= $(this).data("ca_id");
			var base_url 	= $('input[name=base_url]').val();
			window.location.href = base_url + "/crm/clients/editcontact/" + ca_id;
		},
		DeleteAccountInfo : function(){
			 var ca_id = $(this).data('ca_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={cc_id : cc_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/clients/deleteaccountinfo",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  $.clients_datatable.destroy();
					            	  clients_module.DisplayListClients();
					              }
					            }
					        });
					}
				});
		},
		SaveClientInfo : function(){
			clients_module.SaveClientInfoSubmitHandler();
		},
		SaveClientInfoSubmitHandler : function(){
			 var SaveAccountForm = $('#FORM_SAVE_ACCOUNT');
	         var error3 = $('.alert-danger', SaveAccountForm);
	         var success3 = $('.alert-success', SaveAccountForm);

	         SaveAccountForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 fk_account_owner_id : {
	            		 required: true
	            	 },
	            	 ca_account_category : {
	            		 required: true
	            	 },
	            	 ca_account_type_id : {
	                     required: true
	                 },
	                 ca_company_name : {
	                     required: true
	                 },
	                 ca_account_name : {
	                     required: true
	                 }, 
	                 ca_account_phone : {
	                	 required: true
	                 },
	                 ca_account_email : {
	                	 required: true
	                 },
	                 ca_account_mobile : {
	                	 required: true
	                 },
	                 ca_account_number : {
	                	 required :true
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
	    	       
	                var FormDataFields = $("form[id=FORM_SAVE_ACCOUNT]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        $.each($("input[type=file]"), function(i, obj) {
	    	                var name = $(this).attr('name');
	    	                $.each(obj.files,function(j,file){
	    	                        data.append(name, file);
	    	                })
	    	        });

	    	         
	    	        const ca_account_description = $.account_editor.getData();
	    	        data.append("ca_account_description", ca_account_description ); 
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/clients/saveaccountinfo",
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
	    	                 window.location.href = base_url + "/crm/clients";
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		}
};