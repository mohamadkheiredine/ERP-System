customers_module = {
		DisplayListCustomers : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token	 			= $('input[name=_token]').val();
		    var page_number = $('input[name=page_number]').val();
		    var search_query = $('input[name=search_query]').val();
		    var params = { _token : _token , page_number : page_number , search_query : search_query};
		    $.ajax
	        ({
	            url : base_url + "/request/customers/displaylist",
	            data : params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	$('#LstCustomers').html(response.display);

	            	 $('.group-checkable').change(function() {
	                        var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
	                        var checked = $(this).prop("checked");
	                        $(set).each(function() {
	                            $(this).prop("checked", checked);
	                        });
	                        $.uniform.update(set);
	                    });
	            	$("a[id*=EDIT_CUSTOMER_]").on("click",customers_module.EditCustomerInfo);
	            	$("a[id*=DELETE_CUSTOMER_]").on("click",function(){
	            		var ic_id = $(this).data('ic_id');
	            		customers_module.DeleteCustomerInfo(ic_id);
	            	});

	            	 $.pagination = $('#CustomersPagination').twbsPagination({
                         totalPages: response.total_pages,
                         visiblePages: 7,
                         onPageClick: function (event, page) {
                              $('input[name=page_number]').val(page);
                              customers_module.DisplayListCustomers();
                         }
                     });

	            }
	        });
		},
                QuickAction : function(){
                    let action = $(this).data('action_type');
                    switch(action)
                    {
                        case 'DOWNLOAD_TEMPLATE':
                        {
                            customers_module.DownloadTemplate();
                        }
                        break;
                        case 'IMPORT':
                        {
                            customers_module.ImportListCustomers();
                        }
                        break;
                         case 'EXPORT_AS_CSV':
                        {

                        }
                        break;
                        case "CREATE_SALES_ORDER":
                        {
                            customers_module.CreateSalesOrder();
                        }
                    }
                },
                CreateSalesOrder : function(){
                    var _token = $('input[name=_token]').val();
                    let ic_id = $('.checkboxes:checked').val();
                    var base_url = $('#BASE_URL').val();
                    window.location.href = base_url + "/sales/orders/addform?customer_id=" + ic_id;

                },
                DownloadTemplate : function(){

	                var base_url = $('#BASE_URL').val();
                        var _token = $('input[name=_token]').val();
                   $.ajax({
                        url: base_url + '/request/customers/download-template',
                        type: 'POST',
                        data: { _token : _token },
                        success: function(response) {
                            const url = window.URL.createObjectURL(new Blob([response]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'lstcustomerstemplate.csv');
                        document.body.appendChild(link);
                        link.click();
                        }
                    });

                },
                UploadListCustomers : function(e){
                    	 var ImportCustomerForm = $('#FORM_IMPORT_CUSTOMERS');
	         var error3 = $('.alert-danger', ImportCustomerForm);
	         var success3 = $('.alert-success', ImportCustomerForm);

	         ImportCustomerForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
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

                     var FormDataFields = $("form[id=FORM_IMPORT_CUSTOMERS]");
                    var data = new FormData();
                    var index = 0;

                    $.each($("input[type=file]"), function(i, obj) {
                            var name = $(this).attr('name');
                            $.each(obj.files,function(j,file){
                                    data.append(name, file);
                            });
                    });

                    FormDataFields.find('input').each(function(){
                            data.append($(this).attr('name'), $(this).val() );
                    });
                    var base_url = $('#BASE_URL').val();
                      $.ajax
	    	        ({
	    	            url : base_url + "/request/customers/importlistcustomers",
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
                                $('input[name=page_number]').val(1);
                                customers_module.DisplayListCustomers();
                                $("#modal_import").modal('toggle')
	    	              }
	    	            }
	    	        });

	             }

	         });

                },
		SaveCustomerInfo : function(){
			return customers_module.SaveCustomerInfoSubmitHandler();
		},
		SaveCustomerInfoSubmitHandler : function(){
			 var SaveCustomerForm = $('#FORM_SAVE_CUSTOMER');
	         var error3 = $('.alert-danger', SaveCustomerForm);
	         var success3 = $('.alert-success', SaveCustomerForm);

	         SaveCustomerForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	                 ic_customer_code : {
	                     required: true
	                 },
	                 ic_customer_name : {
	                	 required: true
	                 },
	                 ic_account_number : {
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

	                var FormDataFields = $("form[id=FORM_SAVE_CUSTOMER]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        $.each($("input[type=file]"), function(i, obj) {
	    	                var name = $(this).attr('name');
	    	                $.each(obj.files,function(j,file){
	    	                        data.append(name, file);
	    	                })
	    	        });

	    	        FormDataFields.find('input,select,textarea').each(function(){
	    	                data.append($(this).attr('name'), $(this).val() );
	    	        });
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/savecustomerinfo",
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
	    	                 window.location.href = base_url + "/inventory/customers";
	    	              }
	    	            }
	    	        });

	             }

	         });
		},
		AddNewAccount : function(){
			var account_ref 	= $('input[name=aa_account_ref]').val();
			var parent_account 	= $('select[name=aa_parent_account]').val();
			var account_label 	= $('input[name=aa_account_label]').val();
			var _token 			= $('input[name=_token]').val();
		     var base_url = $('#BASE_URL').val();
			var params = {account_ref : account_ref , parent_account : parent_account , account_label : account_label , _token : _token};
			$.ajax
	        ({
	            url : base_url + "/request/customers/saveaccaccounting",
	            data : params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  let $dropdown = $('select[name=ic_account_number]');
	            	  $dropdown.select2('destroy');
	            	  $dropdown.append("<option selected='selected' value='" +  response.aa_id + "'>" + response.accounting_label + "</option>");
	            	  $dropdown.select2();
	            	  $("#AccountAccounting").modal('toggle');
	              }
	            }
	        });

		},
		EditCustomerInfo : function(){
			var ic_id = $(this).data("ic_id");
			var base_url = $('#BASE_URL').val();
			window.location.href = base_url + "/inventory/customers/editform/" + ic_id;
		},
		DeleteCustomerInfo : function(ic_id){
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={ic_id : ic_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/deletecustomerinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  customers_module.DisplayListCustomers();
				              }
				            }
				        });
				}
			});
		}
};
