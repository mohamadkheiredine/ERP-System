 /**
 * 
 */

timesheet_module = {
		DisplayListMonthlyTimeSheet : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/displaylisttimesheet",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#TimeSheetManagement').html(response.display);
		        }
		    });
		},
		DisplayListOnlineEmployees : function(){
			var base_url 	= $('input[name=base_url]').val();
		    var _token 		= $('input[name=_token]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/displaylistonlineemployees",
		        data : { _token : _token },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstOnlineEmployees').html(response.display);
		        }
		    });
		},
		DisplayListTransportationEmployees : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
		    var te_date 		= $('input[name=te_date]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/displaylisttransportationemployees",
		        data : { _token : _token , te_date : te_date },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstTransEmployees').html(response.display);
		        }
		    });
		},
		DisplayListHolidaysEmployees : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
			var ts_year 	= $('select[id=TS_YEAR]').val();
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/displaylistholidayemployees",
		        data : { _token : _token , ts_year : ts_year },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$('#LstHolidayEmployees').html(response.display);
		        }
		    });
		},
		CheckInCheckOutTimesheet : function(){
			if($('#TimeSheetManagement tr:last td:nth-child(4)').html() != '')
			{
				var base_url 	= $('input[name=base_url]').val();
			    var _token 		= $('input[name=_token]').val();
			    $.ajax
			    ({
			        url : base_url + "/request/timesheet/checkincheckout",
			        data : { _token : _token , action : 'checkin' },
		            method : 'post',
		            dataType : "json",
		            beforeSend : function(){
		            },
			        success : function(response){
			        	timesheet_module.DisplayListMonthlyTimeSheet();
			        }
			    });
			}
			else
			{
				$('#CheckOutInfo').modal('toggle');
			}
		},
		SaveCheckOutInformation : function(){
			return timesheet_module.SaveCheckOutInformationSubmitHandler();
		},
		SaveCheckOutInformationSubmitHandler : function(){
			 var TimesheetForm = $('#FRM_CHECKIN_TIMESHEET');
	         var error3 = $('.alert-danger', TimesheetForm);
	         var success3 = $('.alert-success', TimesheetForm);

	         TimesheetForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 ts_day_type : {
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
	    	      
	    	        var str_params = $("#FRM_CHECKIN_TIMESHEET").serialize();
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/timesheet/checkincheckout",
	    	            data : str_params,
	    	            method : 'post',
	    	            dataType : "json",
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  timesheet_module.DisplayListMonthlyTimeSheet();
	    	            	  $('#CheckOutInfo').modal('toggle');
	    	              }
	    	            }
	    	        });
	             }

	         });
		},
		DisplayAdminTimesheet : function(){
			var base_url 	= $('input[name=base_url]').val();
			var _token 		= $('input[name=_token]').val();
			var ts_user 		= $('select[name=ts_user]').val();
		    var ts_date 		= $('input[name=ts_date]').val();
		    if(ts_user == '')
	    	{
		    	bootbox.alert("Please select a Username to find the information about today's timesheet");
		    	return false;
	    	}
		    
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/admintimesheet",
		        data : { _token : _token , ts_user : ts_user , ts_date : ts_date },
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	$("#TimeSheetManagement").html(response.display);
		        	$('select').select2();
		        	$('.timesheet').each(function(){
		        	 
                                        
                                        new tempusDominus.TempusDominus(this, {
                                    display: {
                                        viewMode: "clock",
                                        components: {
                                            decades: false,
                                            year: false,
                                            month: false,
                                            date: false,
                                            hours: true,
                                            minutes: true,
                                            seconds: false
                                        }
                                    }
                                });
                                        
		        	})
		        }
		    });
		},
		SaveDailyTimesheetRecords : function(){
			var base_url 		= $('input[name=base_url]').val();
			var ts_user 		= $('select[name=ts_user]').val();
			var ts_date 		= $('input[name=ts_date]').val();
		    var ts_day_type 	= $('select[name=ts_day_type]').val();
		    
		    if(ts_day_type == "")
	    	{
		    	bootbox.alert("Please select Day Type Before save timesheet");
		    	$('select[name=ts_day_type]').focus();
		    	return false;
	    	}
		    
		    var str_params = $("#FRM_TIMESHEET_MANAGEMENT").serialize();
		    str_params = str_params + "&ts_user=" + ts_user + "&ts_date=" + ts_date;
		    $.ajax
		    ({
		        url : base_url + "/request/timesheet/saveadmintimesheet",
		        data : str_params,
	            method : 'post',
	            dataType : "json",
	            beforeSend : function(){
	            },
		        success : function(response){
		        	bootbox.alert(response.error_msg,function(){
		        		timesheet_module.DisplayAdminTimesheet();
		        	})
		        }
		    });
		},
		ExportAsCsvTransportationEmployees : function(){
			var base_url = $('#BASE_URL').val();
			var ts_date 		= $('input[name=te_date]').val();
			window.location.href = base_url + "/request/ExportCSV/transportationemployees/" + ts_date;
		},  
		PrintTransportationEmployees : function(){
			var base_url = $('#BASE_URL').val();
			var ts_date 		= $('input[name=te_date]').val();
			window.location.href = base_url + "/request/print/transportationemployees/" + ts_date;
		}
};