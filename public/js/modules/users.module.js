/**
 *
 */

var users_module = {
			DisplayListUsers : function(){
				var base_url 			= $('input[name=base_url]').val();
				var general_search 			= $('input[name=general_search]').val();
			    var _token	 			= $('input[name=_token]').val();
			    var params = { _token : _token , general_search : general_search };
			    $.ajax
		        ({
		            url : base_url + "/request/displayusersManagement",
		            data : params,
		            dataType : "json",
		            type : "POST",
		            success : function(response){
		            	$('#LstUsers').html(response.display);
		            	   $.pagination = $('#UsersPagination').twbsPagination({
	                           totalPages: response.total_pages,
	                           visiblePages: 7,
	                           onPageClick: function (event, page) {
	                                $('input[name=page_number]').val(page);
	                                users_module.DisplayListUsers();
	                           }
	                       });
		            }
		        });
			},
			EditUserInfo : function(){
			    var user_id 	= $(this).data('user_id');
			    var base_url 	= $('input[name=base_url]').val();
			    window.location.href = base_url + "/administrator/edituser/" + user_id;
			},
			DeleteUserInfo : function(){
				 var user_id 	= $(this).data('user_id');
				 bootbox.confirm("Are you sure you want to delete ?", function(result){
						//result
						if(result == true)
						{
							var base_url = $('#BASE_URL').val();
							var _token = $('input[name=_token]').val();
							var str_params ={user_id : user_id , _token : _token};
							$.ajax
							({
								url : base_url + "/request/users/deleteuserinfo",
								data : str_params,
								dataType : "Json",
								type : "POST",
								success : function(response){
									if(response.is_error == 0)
									{
										$.user_datatable.destroy();
										users_module.DisplayListUsers();
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
			SaveUserInfo : function(){
				return users_module.SaveUserInfoSubmitHandler();
			},
			SaveUserInfoSubmitHandler : function(){
				 var ProfileForm = $('#FORM_SAVE_USERS');
		         var error3 = $('.alert-danger', ProfileForm);
		         var success3 = $('.alert-success', ProfileForm);
		         error3.html('<strong>Error!</strong> You have some form errors. Please check below.');

		         ProfileForm.validate({
		             errorElement: 'span', //default input error message container
		             errorClass: 'help-block help-block-error', // default input error message class
		             focusInvalid: false, // do not focus the last invalid input
		             ignore: "", // validate all fields including form hidden input
		             rules: {
		            	 u_username: {
		            		 minlength: 4,
		            		 required: true
		            	 },
		            	 u_fullname: {
		            		 minlength: 4,
		            		 required: true
		            	 },
		            	 u_attendance_code : {
		            		required : true,
		            		number : true
		            	 },
		            	 u_password: {
		            		 minlength: 8,
		            	 },
		            	 retype_u_password: {
		                     minlength: 8,
		                     equalTo: "#U_PASSWORD"
		                   },
		                   u_user_type : {
		            		 required: true
		            	 },
		            	 u_date_birth : {
		                     required: true
		                 },
		                 u_gender : {
		                	 required: true
		                 },
		                 u_role : {
		                	 required: true
		                 },
		                 u_employment_date : {
		                	 required: true
		                 },
		                 u_daily_working_hours : {
		                	 number : true,
		                	 required: true
		                 },
		                 u_sales_commission : {
		                	 number :true 
		                 },
		                 u_user_sallary : {
		                	number :true 
		                 },
		                 u_email: {
		                 	email : true,
		                 	 minlength: 5,
		                     required: true
		                 }
		             },

		             messages: { // custom messages for radio buttons and checkboxes

		             },
		             errorPlacement: function (error, element) { // render error placement for each input type
		            	 console.log(element.parent(".form-group").length);
		                 if (element.parent(".form-group").length > 0) {
		                     error.insertAfter(element.parent(".form-group"));
		                 } else if (element.attr("data-error-container")) {
		                     error.appendTo(element.attr("data-error-container"));
		                 } else if (element.parents('.radio-list').length > 0) {

		                     error.appendTo("#GenderError");
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
		    	        var _token = $('input[name=_token]').val();

		                // Create a formdata object and add the files
		                var FormDataFields = $("#FORM_SAVE_USERS");
		                var data = new FormData();
		                var index = 0;

		                $.each($("input[type=file]"), function (i, obj) {
		                    var name = $(this).attr('name');
		                    $.each(obj.files, function (j, file) {
		                        data.append(name, file);
		                    });
		                });

		                FormDataFields.find('input,select,textarea').each(function () {
		                	if($(this).attr('name') == "u_is_active")
		        			{
	    	        			var u_is_active = 0;
	    	        			if($("input[name=u_is_active]:checked").length == 1)
		        				{
	    	        				u_is_active = 1;
		        				}
	    	        			data.append("u_is_active", u_is_active );
		        			}
		                	else if($(this).attr('name') == "u_has_insurance"){
		                		var u_has_insurance = 0;
	    	        			if($("input[name=u_has_insurance]:checked").length == 1)
		        				{
	    	        				u_has_insurance = 1;
		        				}
	    	        			data.append("u_has_insurance", u_has_insurance );
		                	}
		                	else if($(this).attr('name') == "u_cnss_number"){
		                		var u_cnss_number = 0;
	    	        			if($("input[name=u_cnss_number]:checked").length == 1)
		        				{
	    	        				u_cnss_number = 1;
		        				}
	    	        			data.append("u_cnss_number", u_cnss_number );
		                	}
	    	        		else
		        			{
	    	        			data.append($(this).attr('name'), $(this).val() );
		        			}
		                });

		                $.ajax
                        ({
                            url: base_url + '/request/users/saveuserinfo',
                            data: data,
                            async: false,
                            cache: false,
                            method: 'post',
                            contentType: false,
                            processData: false,
                            dataType: "json",
                            beforeSend: function () {
                            },
                            success: function (response) {
                                if (response.is_error == 0) {
                                	 window.location.href = base_url + "/administrator/users";
                                } else {
                                    error3.html(response.error_msg);
                                    success3.hide();
                                    error3.show();
                                }
                            }
                        });
		             }

		         });
			}
};