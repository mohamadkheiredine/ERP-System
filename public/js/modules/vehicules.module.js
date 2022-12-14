/**
 * 
 */


var vehicules_module = {
		DisplayListVehicules : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/logistics/listvehicules",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstVehiculesGrid').html(response.display);
                             $('.group-checkable').change(function() {
                                var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                                var checked = $(this).prop("checked");
                                $(set).each(function() {
                                    $(this).prop("checked", checked);
                                });
                                $.uniform.update(set);
                            });

                            /** $('#ProductCategoriesPagination').twbsPagination({
                                 totalPages: response.total_pages,
                                 visiblePages: 7,
                                 onPageClick: function (event, page) {
                                      $('input[name=page_number]').val(page);
                                      prodcategory_module.displayListProductCategories();
                                 }
                             });*/
		        }
		    });
		},
	SaveVehiculesInfo : function(){
		return vehicules_module.SaveVehiculesSubmitHandler();
	},
	SaveVehiculesSubmitHandler : function(){
		 var VehiculesForm = $('#FORM_SAVE_VEHICULE');
         var error3 = $('.alert-danger', VehiculesForm);
         var success3 = $('.alert-success', VehiculesForm);

         VehiculesForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 lv_vehicule_name : {
                     required: true
                   },
                   lv_vehicule_number : {
                     required: true
                   },
                   lv_plate_number : {
                       required: true, 
                     },
                     lv_model_year : {
                         required: true, 
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
    	        
    	        var FormDataFields = $("form[id=FORM_SAVE_VEHICULE]");

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
    	        var str_params = $("#FORM_SAVE_VEHICULE").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/logistics/savevehiculeinfo",
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
    	                 window.location.href = base_url + "/logistics/vehicules";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteVehiculesData : function(){
		 var lv_id = $(this).parents('tr').data('lv_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={lv_id : lv_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/logistics/deletevehiculeinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  vehicules_module.DisplayListVehicules();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditVehiculeForm : function(){
		var lv_id = $(this).parents('tr').data('lv_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/logistics/vehicules/editform/" + lv_id;
	}
};