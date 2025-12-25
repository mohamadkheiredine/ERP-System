/**
 *
 */

farmcycles_module = {
	DisplayListFarmCycles : function(){
		var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val();
	    var page_number 		= $('input[name=page_number]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/farmcycles/displaylist",
	        data : { _token : _token , page_number : page_number },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('#LstFarmCycles').html(response.display);
                if(response.total_pages > 1)
                {
                    $.pagination = $('#FarmCyclesPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=page_number]').val(page);
                            farmcycles_module.DisplayListFarmCycles();
                        }
                    });
                }
	        }
	    });
	},
    DisplayListExpenses : function(){
		var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val();
	    var fc_id 		= $('input[name=fc_id]').val();
	    var epage_number 		= $('input[name=epage_number]').val();
	    $.ajax
	    ({
	        url : base_url + "/request/farmcycles/listexpenses",
	        data : { _token : _token , fc_id : fc_id , page_number :epage_number },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
	        success : function(response){
	        	$('.lstExpenses').html(response.display);
                if(response.total_pages > 1)
                {
                    $.pagination = $('#CycleExpensesPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=epage_number]').val(page);
                            farmcycles_module.DisplayListExpenses();
                        }
                    });
                }
	        }
	    });
	},
	SaveFarmCycleInfo : function(){
		return farmcycles_module.SaveFarmCycleSubmitHandler();
	},
    SaveFarmCycleSubmitHandler : function(){
		 var CycleForm = $('#FORM_SAVE_CYCLES');
         var error3 = $('.alert-danger', CycleForm);
         var success3 = $('.alert-success', CycleForm);

        CycleForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
                 fc_warehouse_id : {
            		 required: true
            	 },
                 fc_farm_name : {
	                 required: true
	               },
                 fc_start_date : {
                     required: true
                 },
                 fc_end_date : {
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
    	        var str_params = $("#FORM_SAVE_CYCLES").serialize();
    	        str_params = str_params;
    	         $.ajax
    	        ({
    	            url : base_url + "/request/farmcycles/savefarmcycleinfo",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	                 window.location.href = base_url + "/production/farmcycles";
    	              }
    	            }
    	        });
             }

         });
	},
	DeleteFarmCyclesData : function(){
		 var fc_id = $(this).data('fc_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={fc_id : fc_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/farmcycles/deletefarmcycleinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "DELETE",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  $.pt_datatable.destroy();
			            	  projecttypes_module.DisplayListProjectTypes();
			              }
			            }
			        });
			}
		});
	},
	EditFarmCyclesInfo : function(){
		var fc_id = $(this).data('fc_id');
	    var base_url = $("#BASE_URL").val();
	    window.location.href = base_url + "/production/farmcycles/editform/" + fc_id;
	}
};
