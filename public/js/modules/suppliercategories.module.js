/**
 * 
 */
suppliercategories_module = {
	displayListSupplierCategories : function(){
		var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/srm/displaylistcategory",
	        data : { _token : _token },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstSupplierCategories').html(response.display);
	        }
	    });
	},
	SaveSupplierCategoryInfo : function(){
		return suppliercategories_module.SaveSupplierCategorySubmitHandler();
	},
	SaveSupplierCategorySubmitHandler : function(){
		 var CategoryForm = $('#FORM_SAVE_CATEGORY');
         var error3 = $('.alert-danger', CategoryForm);
         var success3 = $('.alert-success', CategoryForm);

         CategoryForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 sc_category_title : {
                     required: true
                   }, 
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
    	       
    	        const sc_category_description  = $.editor.getData();
    	         
    	        var str_params = $("#FORM_SAVE_CATEGORY").serialize();
    	        str_params = str_params + "&sc_category_description=" + sc_category_description
    	         $.ajax
    	        ({
    	            url : base_url + "/request/srm/savecategoryinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/srm/suppliercategories";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteCategoriesData : function(){
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
			            url : base_url + "/request/srm/deletecategoryinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.sc_datatable.destroy();
			            	  suppliercategories_module.displayListSupplierCategories();
			              }
			            }
			        });
			}
		});
	},
	EditCategoryInfo : function(){
		var sc_id = $(this).data('sc_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/srm/suppliers/editcategory/" + sc_id;
	}
};