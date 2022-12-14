/**
 * 
 */
company_module = {
		DisplayListCompanies : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    $.ajax
		    ({
		        url : base_url + "/request/companies/listcompanies",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstCompaniesGrid').html(response.display);
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
	SaveCompanyInfo : function(){
		return company_module.SaveCompaniesSubmitHandler();
	},
	SaveCompaniesSubmitHandler : function(){
		 var CompanyForm = $('#FORM_SAVE_COMPANY');
         var error3 = $('.alert-danger', CompanyForm);
         var success3 = $('.alert-success', CompanyForm);

         CompanyForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 cd_company_name : {
                     required: true
                   },
                   cd_company_owner : {
                	   required: true
                   },
                   cd_company_email : {
                       required: true,
                       email : true
                     },
                     cd_transportation_fees : {
                         required: true,
                         number : true
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
    	        
    	        var FormDataFields = $("form[id=FORM_SAVE_COMPANY]");

    	        var data = new FormData();
    	        var index = 0;

    	        $.each($("input[type=file]"), function(i, obj) {
    	                var name = $(this).attr('name');
    	                $.each(obj.files,function(j,file){
    	                        data.append(name, file);
    	                })
    	        });

    	        FormDataFields.find('input,select').each(function(){
    	        		if($(this).attr('name') == "cd_primary_company")
	        			{
    	        			var cd_primary_company = 0;
    	        			if($("input[name=cd_primary_company]:checked").length == 1)
	        				{
    	        				cd_primary_company = 1;
	        				}
    	        			data.append("cd_primary_company", cd_primary_company );
	        			}
    	        		else
	        			{
    	        			data.append($(this).attr('name'), $(this).val() );
	        			}
    	                
    	        });
    	        const cd_about_company = $.editor.getData();
    	        
    	        data.append("cd_about_company", cd_about_company );
    	        var str_params = $("#FORM_SAVE_COMPANY").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/companies/savecompanyinfo",
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
    	                 window.location.href = base_url + "/system/companies";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteProductCategoryData : function(){
		 var cd_id = $(this).data('cd_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={cd_id : cd_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/companies/deletecompanyinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  company_module.DisplayListCompanies();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditCompanyForm : function(){
		var cd_id = $(this).data('cd_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/system/companies/editform/" + cd_id;
	}
};