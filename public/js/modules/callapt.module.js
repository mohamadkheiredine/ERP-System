callapt_module = {
    DisplayListAppointments : function(){
        	var base_url 			= $('input[name=base_url]').val();
                var _token	 			= $('input[name=_token]').val();
                var ld_apt_date	 	= $('input[name=ld_apt_date]').val();
                var params = { _token : _token , ld_apt_date : ld_apt_date };
		$.ajax
	        ({
	            url : base_url + "/request/callcenter/displaylistappointments",
	            data : params,
	            dataType : "json",
	            type : "get",
	            success : function(response){
	            	$('#LstLeadAppts').html(response.display);
	            }
	        });
    },
    DisplayListTodaysAppt : function(){
        	var base_url 			= $('input[name=base_url]').val();
                var _token	 			= $('input[name=_token]').val();
                var display_type	 	= $('input[name=display_type]').val();
                var current_date	 	= $('input[name=ca_appointment_date]').val();
                var params = { _token : _token , display_type : display_type , current_date : current_date};
		$.ajax
	        ({
	            url : base_url + "/request/callcenter/listappointmentsbydate",
	            data : params,
	            dataType : "json",
	            type : "get",
	            success : function(response){
	            	$('#LstAppointments').html(response.display);
	            }
	        });
    },
    GetLeadInformation : function(){
        let lead_id = $('select[name=lead_id]').val();
        var base_url 			= $('input[name=base_url]').val();
        var _token	 			= $('input[name=_token]').val();
        var params = { _token : _token , lead_id : lead_id };
        $.ajax
        ({
            url : base_url + "/request/crm/getleadinfo",
            data : params,
            dataType : "json",
            type : "get",
            success : function(response){

            }
        });
    },
    QuickActionAppointments : function(){
        let action_type = $(this).data('action_type');
        switch (action_type) {
            case "DOWNLOAD_APPOINTMENT":
            {
                let ca_id = $('input[name=ca_id]').val();
                if(ca_id == 0)
                {
                    bootbox.alert('Please select Appointment to Download Form');
                    return;
                }
                var base_url 	= $('input[name=base_url]').val();
                let url = base_url + "/callcenter/appointments/downloadapt/" + ca_id;
                window.open(url,'_blank');
                window.open(url);
            }
            break;
        }
    },
    DownloadListCallbackLeadsReports : function(){
        var base_url 	= $('input[name=base_url]').val();
        let url = base_url + "/callcenter/leads/downloadcallbackleads";
        window.open(url,'_blank');
        window.open(url);
    },
    DownloadlistAppointmentsReport : function(){
        var base_url 	= $('input[name=base_url]').val();
	    var _token 		= $('input[name=_token]').val()
	    var ca_appointment_date = $('input[name=ca_appointment_date]').val();
             $.ajax
            ({
                url : base_url + "/request/appointments/generateappointmentsreport",
                data : { _token : _token , ca_appointment_date : ca_appointment_date},
                method : 'post',
                 xhrFields: {
                    responseType: 'blob' // Set the response type to blob
                },
                success: function(blob, status, xhr) {
                    // Get the filename from the Content-Disposition header if available
                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    // Fallback filename if none is provided
                    if (!filename) {
                        filename = "reporttodaysappt.pdf";
                    }

                    // Create a temporary link element
                    var link = document.createElement('a');
                    var url = window.URL.createObjectURL(blob);
                    link.href = url;
                    link.download = filename;

                    // Append link to the body
                    document.body.appendChild(link);
                    link.click();

                    // Remove the link and revoke the object URL
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                },
                error: function(xhr, status, error) {
                    console.error("File download failed:", error);
                }
        });
    },
    GetLeadAppointmentInformation : function(){
        var base_url 			= $('input[name=base_url]').val();
        var _token	 			= $('input[name=_token]').val();
        var ca_id	 	= $(this).data('ca_id');
        var params = { _token : _token , ca_id : ca_id };
        $.ajax
        ({
            url : base_url + "/request/callcenter/getappointmentinformation",
            data : params,
            dataType : "json",
            type : "get",
            success : function(response) {
                $('input[name=ca_id]').val(response.appointment_array.ca_id);
                $('select[name=lead_id]').val(response.appointment_array.ca_lead_id).trigger('change');
                $('.LeadDropdown').css({display: "none"});
                $('select[name=cl_sales_id]').val(response.appointment_array.ca_salesman_id).trigger('change');
                $('select[name=cl_sales_id]').select2('destroy').select2();
                $('select[name=cl_telemarketing_id]').val(response.appointment_array.ca_telemarketing_id).trigger('change');
                $('select[name=cl_telemarketing_id]').select2('destroy').select2();
                $('input[name=ca_apt_date]').val(response.appointment_array.ca_apt_date);
                $('input[name=ca_apt_time]').val(response.appointment_array.ca_apt_time);
                $('select[name=ca_apt_result]').val(response.appointment_array.ca_apt_result).trigger('click');
                $('select[name=ca_apt_result]').select2('destroy').select2();
                $('select[name=cl_lead_type]').val(response.appointment_array.cl_lead_type_id).trigger('click');
                $('select[name=cl_lead_type]').select2('destroy').select2();
                $('input[name=ca_apt_with]').val(response.appointment_array.ca_apt_with);
                $('input[name=ca_apt_job]').val(response.appointment_array.ca_apt_job);
                $('input[name=ca_lead_address]').val(response.appointment_array.ca_lead_address);
                $('input[name=ca_nbr_leads]').val(response.appointment_array.ca_nbr_leads);
                $('input[name=cl_full_name]').val(response.appointment_array.cl_full_name);
                $('input[name=cl_phone]').val(response.appointment_array.cl_mobile);
                $('input[name=cl_area]').val(response.appointment_array.cl_area);
                $('input[name=cl_referred_by]').val(response.appointment_array.cl_referred_by);
                $('textarea[name=ca_apt_notes]').val(response.appointment_array.ca_apt_notes);
                $('textarea[name=ca_apt_details]').val(response.appointment_array.ca_apt_details);
                if (response.appointment_array.ca_lead_confirm == 1)
                    $('input[name=ca_lead_confirm]').attr({'checked': "checked"});
                else
                    $('input[name=ca_lead_confirm]').removeAttr("checked");
            }
        });
    },
    DisplayClosureSalesmanApp : function(){
        var base_url 			= $('input[name=base_url]').val();
        var _token	 			= $('input[name=_token]').val();
        var ca_apt_from_date	 			= $('input[name=ca_apt_from_date]').val();
        var ca_apt_last_date	 			= $('input[name=ca_apt_last_date]').val();
        var cl_sales_id	 			= $('select[name=cl_sales_id]').val();
        var params = { _token : _token , ca_apt_from_date : ca_apt_from_date , ca_apt_last_date : ca_apt_last_date , cl_sales_id : cl_sales_id };
        $.ajax
        ({
            url : base_url + "/request/callcenter/displayclosuresalesmanapp",
            data : params,
            dataType : "json",
            type : "get",
            success : function(response){
                $('#LstPercentageClosure').html(response.display);
            }
        });
    },
    AppQuickAction : function(){
        var action_type = $(this).data('action_type');
        switch(action_type)
        {
            case "DOWNLOAD_PDF":
            {
                callapt_module.downloadPDFReport()
            }
            break;
        }
    },
    downloadPDFReport : function(){
        var base_url 			= $('input[name=base_url]').val();
        var _token	 			= $('input[name=_token]').val();
        var ca_apt_from_date	 			= $('input[name=ca_apt_from_date]').val();
        var ca_apt_last_date	 			= $('input[name=ca_apt_last_date]').val();
        var cl_sales_id	 			= $('select[name=cl_sales_id]').val();
        var params = { _token : _token , ca_apt_from_date : ca_apt_from_date , ca_apt_last_date : ca_apt_last_date , cl_sales_id : cl_sales_id };

        $.ajax
       ({
           url : base_url + "/request/callcenter/downloadclosuresalesapp",
           data : params,
           method : 'get',
            xhrFields: {
               responseType: 'blob' // Set the response type to blob
           },
           success: function(blob, status, xhr) {
               // Get the filename from the Content-Disposition header if available
               var filename = "";
               var disposition = xhr.getResponseHeader('Content-Disposition');
               if (disposition && disposition.indexOf('attachment') !== -1) {
                   var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                   var matches = filenameRegex.exec(disposition);
                   if (matches != null && matches[1]) {
                       filename = matches[1].replace(/['"]/g, '');
                   }
               }

               // Fallback filename if none is provided
               if (!filename) {
                   filename = "downloaded_file.pdf";
               }

               // Create a temporary link element
               var link = document.createElement('a');
               var url = window.URL.createObjectURL(blob);
               link.href = url;
               link.download = filename;

               // Append link to the body
               document.body.appendChild(link);
               link.click();

               // Remove the link and revoke the object URL
               document.body.removeChild(link);
               window.URL.revokeObjectURL(url);
           },
           error: function(xhr, status, error) {
               console.error("File download failed:", error);
           }
   });
    },
    DisplayListAllAppointments : function(){
        	var base_url 			= $('input[name=base_url]').val();
                var _token	 			= $('input[name=_token]').val();
                var lead_id	 	= $('select[name=lead_id]').val();
                var ld_apt_date	 	= $('input[name=ld_apt_date]').val();
                var ld_from_apt_date	 	= $('input[name=ld_from_apt_date]').val();
                var ld_to_apt_date	 	= $('input[name=ld_to_apt_date]').val();
                var ca_salesman_id	 	= $('select[name=ca_salesman_id]').val();
                var ap_apt_result	 	= $('select[name=ap_apt_result]').val();
                var phone_number	 	= $('#PHONE_NUMBER').val();
                var params = { _token : _token , phone_number : phone_number ,  ap_apt_result : ap_apt_result ,  lead_id : lead_id , ca_salesman_id : ca_salesman_id , ld_apt_date : ld_apt_date , ld_from_apt_date : ld_from_apt_date , ld_to_apt_date : ld_to_apt_date };
		$.ajax
	        ({
	            url : base_url + "/request/callcenter/displaylistappointments",
	            data : params,
	            dataType : "json",
	            type : "get",
	            success : function(response){
	            	$('#LstLeadAppts').html(response.display);
	            }
	        });
    },
    SaveLeadAppointmentInfo : function(){
        return callapt_module.SaveLeadAppointmentSubmitHandler();
    },
    SaveLeadAppointmentSubmitHandler : function(){
        	 var SaveAptForm = $('#FRM_CREATE_APT');
	         var error3 = $('.alert-danger', SaveAptForm);
	         var success3 = $('.alert-success', SaveAptForm);

	         SaveAptForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	                 ca_apt_date : {
	                     required: true
	                 },
                         ca_apt_time : {
                             required : true
                         },
                         ca_apt_result : {
                             required : true
                         },
                     ca_nbr_leads : {
                         required : true,
                         min:0
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

	                var FormDataFields = $("form[id=FRM_CREATE_APT]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        FormDataFields.find('input,select,textarea').each(function(){
                           if($(this).attr('type') != 'checkbox')
                           {
                                 var name = $(this).attr('name');
                                var val = $(this).val();
                                data.append( name, val );
                           }
                           else
                           {
                                var name = "ca_lead_confirm";
                                var val = $('input[name=ca_lead_confirm]:checked').length;
                                data.append( name, val );
                           }
	    	        });

	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/saveappointmentinfo",
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
                                  $("form[id=FRM_CREATE_APT]").trigger("reset");
                                  $("button[name=btn_search]").trigger("click");
                              $('.LeadDropdown').css({display : ""});
	    	              }
	    	            }
	    	        });

	             }

	         });
    },
    DeleteLeadAppointment : function(){
         var ca_id = $(this).data('ca_id');
            bootbox.confirm("Are you sure you want to delete ?", function(result){
                    //result
                    if(result == true)
                    {
                          var base_url = $('#BASE_URL').val();
                          var _token = $('input[name=_token]').val();
                            var str_params ={ca_id : ca_id , _token : _token};
                             $.ajax
                            ({
                                url : base_url + "/request/callcenter/deleteleadapp",
                                data : str_params,
                                dataType : "Json",
                                type : "delete",
                                success : function(response){
                                  if(response.is_error == 0)
                                  {
                                       callapt_module.DisplayListAppointments();
                                  }
                                }
                            });
                    }
            });
    }
};
