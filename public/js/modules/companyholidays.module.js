/**
 * 
 */
holidays_module = {
		displayListCompanyHolidays : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val()
		    var page_number = $('input[name=page_number]').val();
		    var holiday_year = $('select[name=holiday_year]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/listholidays",
		        data : { _token : _token , page_number : page_number , holiday_year : holiday_year },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		            $('.LstHolidaysGrid').html(response.display);
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
		displayListHolidayRequests : function(){
			var base_url 			= $('input[name=base_url]').val();
			var _token 				= $('input[name=_token]').val();
			var hr_department_id 	= $('select[name=hr_department_id]').val();
		    var hr_user_id 			= $('select[name=hr_user_id]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/listholidayrequests",
		        data : { _token : _token , hr_department_id : hr_department_id , hr_user_id : hr_user_id },
	            method : 'post',
	            dataType : "json",
		        success : function(response){
		            $('#LstHolidayRequests').html(response.display);
		            $.hr_datatable = $('.m_datatable').mDatatable({
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
		QuickActionRequests : function(){
			var action_type = $(this).data('action_type');
			if($(".checkboxes:checked").length == 0)
			{
				bootbox.alert("Please select a Request to do any action");
				return false;
			}
			switch(action_type)
			{
				case "CHANGE_REQUEST_STATUS":
				{
					holidays_module.ChangeRequestStatus();
				}
				break;
			}
		},
		ChangeRequestStatus : function(){
			var tr_ids = [];
			$(".checkboxes:checked").each(function(){
				var tr_id = $(this).val();
				tr_ids.push(tr_id);
			}); 
			var str_tr = tr_ids.join(",");
			$("input[name=tr_request_ids]").val(str_tr);
			$('#ApproveDenyModel').modal('toggle');
		},
		SaveChangingStatus : function(){
			var base_url = $("input[name=base_url]").val();
			
			var str_params = $("#FRM_CHANGE_STATUS").serialize();
			$.ajax
			({
				url : base_url + "/request/timesheet/changerequeststatus",
				data : str_params,
				method : 'post',
				dataType : "json",
				beforeSend : function(){
				},
				success : function(response){
					$('#ApproveDenyModel').modal('toggle');
				}
			});
		},
		SaveYearlyHolidayInfo : function(){
			return holidays_module.SaveYearlyHolidayInfoSubmitHandler();
		},
		SaveYearlyHolidayInfoSubmitHandler : function(){
			var HolidayForm = $('#FORM_SAVE_HOLIDAY');
			var error3 = $('.alert-danger', HolidayForm);
			var success3 = $('.alert-success', HolidayForm);
			
			HolidayForm.validate({
				errorElement: 'span', //default input error message container
				errorClass: 'help-block help-block-error', // default input error message class
				focusInvalid: false, // do not focus the last invalid input
				ignore: "", // validate all fields including form hidden input
				rules: {
					th_year : {
						required: true
					},
					th_holiday_name : {
						required: true
					},
					th_holiday_date : {
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
					
					
					var str_params = $("#FORM_SAVE_HOLIDAY").serialize();
					$.ajax
					({
						url : base_url + "/request/timesheet/saveholidayinfo",
						data : str_params,
						method : 'post',
						dataType : "json",
						beforeSend : function(){
						},
						success : function(response){
							if(response.is_error == 0)
							{
								window.location.href = base_url + "/timesheet/holidays";
							}
						}
					});
				}
				
			});
		},
	SendHolidayRequest : function(){
		return holidays_module.SendHolidayRequestSubmitHandler();
	},
	SendHolidayRequestSubmitHandler : function(){
		 var HolidayRequestForm = $('#FORM_SEND_HOLIDAY_REQUEST');
         var error3 = $('.alert-danger', HolidayRequestForm);
         var success3 = $('.alert-success', HolidayRequestForm);

         HolidayRequestForm.validate({
             errorElement: 'span', //default input error message container
             errorClass: 'help-block help-block-error', // default input error message class
             focusInvalid: false, // do not focus the last invalid input
             ignore: "", // validate all fields including form hidden input
             rules: {
            	 tr_holiday_date_from : {
                     required: true
                 },
                 tr_holiday_date_to : {
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
               // success3.show();
               // error3.hide();
                var base_url = $('#BASE_URL').val();
    	       // var _token = $('input[name=_token]').val();
    	        
 
    	        var str_params = $("#FORM_SEND_HOLIDAY_REQUEST").serialize();
    	         $.ajax
    	        ({
    	            url : base_url + "/request/timesheet/sendholidayrequest",
    	            data : str_params,
    	            method : 'post',
    	            dataType : "json",
    	            beforeSend : function(){
    	            },
    	            success : function(response){
    	              if(response.is_error == 0)
    	              {
    	            	 bootbox.alert(response.error_msg);
    	                 $("#TR_HOLIDAY_DATE_FROM").val("");
    	                 $("#TR_HOLIDAY_DATE_TO").val("");
    	                 $.holiday_editor.setData("");
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
	DeleteHolidayData : function(){
		 var th_id = $(this).data('th_id');
		bootbox.confirm("Are you sure you want to delete ?", function(result){
			//result
			if(result == true)
			{
			      var base_url = $('#BASE_URL').val();
			      var _token = $('input[name=_token]').val();
			        var str_params ={th_id : th_id , _token : _token};
			         $.ajax
			        ({
			            url : base_url + "/request/timesheet/deleteholidayinfo",
			            data : str_params,
			            dataType : "Json",
			            type : "POST",
			            success : function(response){
			              if(response.is_error == 0)
			              {
			            	  holidays_module.displayListCompanyHolidays();
			              }
			            }
			        });
			}
		});
	},
	DisplayEditHolidayForm : function(){
		var th_id = $(this).data('th_id');
	    var base_url = $("#BASE_URL").val(); 
	    window.location.href = base_url + "/timesheet/holidays/editform/" + th_id;
	}
};