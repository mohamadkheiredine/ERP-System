/**
 * 
 */

myprofile_module = {
		DisplayProfileTab : function(){
			var _token = $('input[name=_token]').val();
			var base_url 	= $('input[name=base_url]').val(); 
		    $.ajax
		    ({
		        url : base_url + "/request/myprofile/displayprofiletabs",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		            $('#PROFILE_PAGE').html(response.display);
		            $("select").select2();
			       	 $('#U_DATE_BIRTH').datepicker({
			       		 endDate :'-18y',
			             todayHighlight: true,
			             orientation: "bottom left",
			             templates: {
			                 leftArrow: '<i class="la la-angle-left"></i>',
			                 rightArrow: '<i class="la la-angle-right"></i>'
			             }
			         });
			    	 $('#U_PROFILE_PIC').on('change', function () {
			    	        var countFiles   = $(this)[0].files.length;
			    	        var imgPath      = $(this)[0].value;
			    	        var extn         = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
			    	        var image_holder = $(".ListFiles");
			    	        image_holder.empty();
	
			    	        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
			    	            if (typeof (FileReader) != "undefined") {
			    	                //loop for each file selected for uploaded.
			    	                for (var i = 0; i < countFiles; i++)
			    	                {
			    	                    var reader = new FileReader();
			    	                    reader.onload = function (e) {
			    	                        var base_url = $('#BASE_URL').val();
	
			    	                        $("#IMAGE_PROFILE").attr('src', e.target.result);
			    	                    }
			    	                    image_holder.show();
			    	                    reader.readAsDataURL($(this)[0].files[i]);
			    	                }
	
			    	            }
			    	        }
			    	    });
		            
		        }
		    });
		},
		SaveMyProfileInfo : function(){
			   var base_url = $('#BASE_URL').val();
    	       // var _token = $('input[name=_token]').val();
    	        var str_params = $("#FORM_MY_PROFILE").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/profile/savemyprofileinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                  bootbox.alert(response.error_msg);
    	                  myprofile_module.DisplayProfileTab();
    	              }
    	            }
    	        });
		},
		UploadImageInfo : function(){
			
            // Create a formdata object and add the files
            var FormDataFields = $("#FRM_UPLOAD_IMAGE");
            var data = new FormData();
            var index = 0;

            $.each($("input[type=file]"), function (i, obj) {
                var name = $(this).attr('name');
                $.each(obj.files, function (j, file) {
                    data.append(name, file);
                });
            });

            FormDataFields.find('input,select,textarea').each(function () {
                data.append($(this).attr('name'), $(this).val());
            });

            $.ajax
            ({
                url: base_url + '/request/profile/changeuserpassword',
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
            	  if(response.is_error == 0)
 	              {
 	                  bootbox.alert(response.error_msg);
 	                  myprofile_module.DisplayProfileTab();
 	              }
                	
                }
            });
			
		},
		ChangePassword : function(){
			var UserChangePasswordForm = $('#FORM_PROFILE_PASSWORD');
	         var error3 = $('.alert-danger', UserChangePasswordForm);
	         var success3 = $('.alert-success', UserChangePasswordForm);

	         UserChangePasswordForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 uo_old_password : {
	                     required: true
	                 },
	                 un_new_password : {
	                     required: true
	                 },
	                 un_confirm_password : {
	                     required: true,
	                     equalTo: "#UN_NEW_PASSWORD"
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
	    	        
	 
	    	        var str_params = $("#FORM_PROFILE_PASSWORD").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/profile/changeprofilepassword",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	                bootbox.alert(response.error_msg);
	    	              }
	    	            }
	    	        });
	             }

	         });
		}
};