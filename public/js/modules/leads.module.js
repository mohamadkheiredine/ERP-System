/**
 * 
 */
leads_module = {
		DisplayListLeads : function(){
		var base_url 			= $('input[name=base_url]').val();
		var _token	 			= $('input[name=_token]').val();
		var lead_category	 	= $('select[name=lead_category]').val();
		var lead_status	 		= $('select[name=lead_status]').val();
		var cl_sales_id	 		= $('select[name=cl_sales_id]').val(); 
                if(cl_sales_id == '')
                {
                    return false;
                }
                
		var params = { _token : _token , lead_category : lead_category , lead_status : lead_status , cl_sales_id : cl_sales_id };
		$.ajax
	        ({
	            url : base_url + "/request/leads/displaylist",
	            data : params,
	            dataType : "json",
	            type : "POST",
	            success : function(response){
	            	$('#LstLeads').html(response.display);
                        $('.group-checkable').change(function() {
                                           var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                                           var checked = $(this).prop("checked");
                                           $(set).each(function() {
                                               $(this).prop("checked", checked);
                                           });
                                           $.uniform.update(set);
                        }); 
                        if(response.total_pages > 0)
                        {
                            $('#LeadsPagination').twbsPagination({
	                         totalPages: response.total_pages,
	                         visiblePages: 7,
	                         onPageClick: function (event, page) {
	                              $('input[name=page_number]').val(page);
	                              leads_module.DisplayListLeads();
	                         }
	                     });
			}
	            }
	        });
		},
                DisplayListLeadResults : function(){
                    var base_url 			= $('input[name=base_url]').val();
                    var _token	 			= $('input[name=_token]').val();
                    var lr_ids = [];
			$(".checkboxes:checked").each(function(){
				var lr_id = $(this).val();
				lr_ids.push(lr_id);
			}); 
			var str_lr = lr_ids.join(",");
		    var params = { _token : _token ,lead_id : str_lr };
		    $.ajax
                    ({
                        url : base_url + "/request/leads/displaylistleadresults",
                        data : params,
                        dataType : "json",
                        type : "get",
                        success : function(response){
                            $("#LstLeadResults").html(response.display);
                        }
                    });
                },
                SelectLeadRecord : function(){
                    $('#LstLeads tr').each((index,item) => {
                       $(item).find('input[type=checkbox]').removeAttr('checked');
                       $(item).removeClass('SelectedRow');
                    })
                    $(this).find('input[type=checkbox]').attr('checked',true);
                    $(this).addClass('SelectedRow');
                    $("input[name=lr_lead_ids]").val($(this).find('input[type=checkbox]').val());
                    leads_module.DisplayListLeadResults();
                },
		SaveLeadsInfo : function(){
			return leads_module.SaveLeadsInfoSubmitHandler();
		},
		SaveLeadsInfoSubmitHandler : function(){
			 var SaveLeadForm = $('#FORM_SAVE_LEAD');
	         var error3 = $('.alert-danger', SaveLeadForm);
	         var success3 = $('.alert-success', SaveLeadForm);

	         SaveLeadForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	                 cl_full_name : {
	                     required: true
	                 },
                         cl_referred_by : {
                             required : true
                         },
                         cl_full_name : {
                             required : true
                         },
                         cl_sheet_number : {
                             required : true
                         },
                         cl_telemarketing_id : {
                             required : true
                         },
                         cl_mobile : {
                             minlength: 10,
                             required:true
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
	    	       
	                var FormDataFields = $("form[id=FORM_SAVE_LEAD]");

	    	        var data = new FormData();
	    	        var index = 0;

	    	        FormDataFields.find('input,select').each(function(){
                            var name = $(this).attr('name');
                            var val = $(this).val();
                            data.append( name, val );
	    	        	 
	    	        });
                        
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/saveleadinfo",
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
                                  if($('input[name=cl_id]').length > 0)
                                  {
                                       window.location.href = base_url + "/crm/leads";
                                  }
                                  else
                                  {
                                      
                                      let cl_sheet_number = $('input[name=cl_sheet_number]').val();
                                      let cl_full_name = $('input[name=cl_full_name]').val();
                                      let cl_region = $('input[name=cl_region]').val();
                                      let cl_area = $('input[name=cl_area]').val();
                                      let cl_sales_id = $('select[name=cl_sales_id] option:selected').text();
                                      let cl_telemarketing_id = $('select[name=cl_telemarketing_id] option:selected').text();
                                      let cl_lead_type_id = $('select[name=cl_lead_type_id]').text();
                                      let cl_referred_by = $('input[name=cl_referred_by]').val();
                                      let cl_mobile = $('input[name=cl_mobile]').val();
                                      
                                      let leads = $('#LstLeads').html();
                                      var length = $('#LstLeads tr').length;
                                      var index = parseInt(length) + 1;
                                      leads += "<tr>"; 
                                      leads += "<td>" + index + "</td>"; 
                                      leads += "<td>" + cl_sheet_number + "</td>"; 
                                      leads += "<td>" + cl_full_name + "</td>"; 
                                      leads += "<td>" + cl_area + "</td>"; 
                                      leads += "<td>" + cl_sales_id + "</td>"; 
                                      leads += "<td>" + cl_telemarketing_id + "</td>"; 
                                      leads += "<td>" + cl_mobile + "</td>"; 
                                      leads += "<td>" + cl_referred_by + "</td>"; 
                                      leads += "</tr>"; 
                                      
                                      $('#LstLeads').html(leads);
                                      
                                      $('#CL_FULL_NAME').val('');
                                      $('#CL_MOBILE').val('');
                                      $('#CL_REGION').val('');
                                      $('#CL_AREA').val('');
                                  }
	    	                
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		},
                DisplayExistingRecordLead : function(){
                    var cl_mobile = $("#CL_MOBILE").val();
                    var base_url =  $('input[name=base_url]').val();
                    var _token =  $('input[name=_token]').val();
                     $.ajax
                    ({
                        url : base_url + "/request/leads/checkleadexistbymobile",
                        data : { _token : _token , cl_mobile : cl_mobile },
                        method : 'post',
                        dataType : "json",
                        success : function(response){
                            if(response.is_error == 1)
                            {
                                alert(response.is_error );
                                $("#LstExistingLeads").html(response.display);
                                $(".ExistingLeadTabs").css('display','');
                            }
                            else
                            {
                                $(".ExistingLeadTabs").css('display','none');
                            }
                            
                        }
                    });
                },
		DeleteLeadInfo : function(){
			 var cl_id = $(this).data('cl_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
				      var base_url = $('#BASE_URL').val();
				      var _token = $('input[name=_token]').val();
				        var str_params ={cl_id : cl_id , _token : _token};
				         $.ajax
				        ({
				            url : base_url + "/request/leads/deleteleadinfo",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  leads_module.DisplayListLeads();
				              }
				            }
				        });
				}
			});
		},
		EditLeadInfo : function(){
			var cl_id = $(this).data('cl_id');
			var base_url = $("#BASE_URL").val();
			window.location.href = base_url + "/crm/leads/editform/" + cl_id;
		},
		EditActivityInfo : function(){
			var ca_id = $(this).data('ca_id');
		    var base_url = $("#BASE_URL").val();
		    window.location.href = base_url + "/crm/leads/editactivitryform/" + ca_id;
		},
		ChangeLeadsStatus : function(){
			var ls_ids = [];
			$(".checkboxes:checked").each(function(){
				var ls_id = $(this).val();
				ls_ids.push(ls_id);
			}); 
			var str_ls = ls_ids.join(",");
			$("input[name=cs_lead_ids]").val(str_ls);
			$('#ChangeStatusModel').modal('toggle');
			
		},
		AssignLead : function(){
			var ls_ids = [];
			$(".checkboxes:checked").each(function(){
				var ls_id = $(this).val();
				ls_ids.push(ls_id);
			}); 
			var str_ls = ls_ids.join(",");
			$("input[name=la_lead_ids]").val(str_ls);
			$('#AssignLeadModel').modal('toggle');
		},
                AddCallResult : function(){
                    var ls_ids = [];
                    $(".checkboxes:checked").each(function(){
                            var ls_id = $(this).val();
                            ls_ids.push(ls_id);
                    }); 
                    var str_ls = ls_ids.join(",");
                    $("input[name=la_lead_ids]").val(str_ls);
                    $('#AddResultModel').modal('toggle');
                },
		ExportAsCsv : function(){
			var ls_ids = [];
			$(".checkboxes:checked").each(function(){
				var ls_id = $(this).val();
				ls_ids.push(ls_id);
			}); 
			var str_ls = ls_ids.join(",");
			var base_url = $('#BASE_URL').val();
			window.location.href = base_url + "/request/ExportCSV/leads/" + ls_ids;
		},
		ConvertLeadstoAccounts : function(){
			var ls_ids = [];
			$(".checkboxes:checked").each(function(){
				var ls_id = $(this).val();
				ls_ids.push(ls_id);
			}); 
			var str_ls = ls_ids.join(",");
                        let _token = $('input[name=_token]').val();
			var base_url = $('#BASE_URL').val();
                        let url = base_url + '/request/leads/converttoaccounts'
			 $.ajax
		        ({
		            url : url,
		            data : { _token : _token , al_ids : str_ls },
		            dataType : "Json",
		            type : "PUT",
		            success : function(response){
		              if(response.is_error == 0)
		              { 
		            	  	leads_module.DisplayListLeads();
		            		$('#AssignLeadModel').modal('toggle');
		              }
		            }
		        });
		},
		QuickActionLead : function(){
                    var action_type = $(this).data('action_type');
                    if($(".checkboxes:checked").length == 0)
                    {
                            bootbox.alert("Please select a Leads to do any action");
                            return false;
                    }
                    switch(action_type)
                    {
                        case "CHANGE_STATUS":
                        {
                            leads_module.ChangeLeadsStatus();
                        }
                        break;
                        case "ASSIGN_LEAD":
                        {
                                leads_module.AssignLead();
                        }
                        break;
                        case "ADD_CALL_RESULT":
                        {
                            leads_module.AddCallResult();
                        }
                        break;
                        case "EXPORT_AS_CSV":
                        {
                                leads_module.ExportAsCsv();
                        }
                        break;
                        case "CONVERT_LEAD_ACCOUNT":
                        {
                                leads_module.ConvertLeadstoAccounts();
                        }
                        break;
                        case "ADD_APPOINTMENT":
                        {
                                leads_module.CreateLeadAppointment();
                        }
                        break;
                    }
		},
                CreateLeadAppointment : function(){
                     var ls_ids = [];
                    $(".checkboxes:checked").each(function(){
                            var ls_id = $(this).val();
                            ls_ids.push(ls_id);
                    }); 
                    var str_ls = ls_ids.join(",");
                    var base_url = $('input[name=base_url').val();
                    window.location.href = base_url + "/leads/createappointment/" + str_ls;
                },
		SaveChangeLeadsStatus : function(){
			var base_url = $('#BASE_URL').val();
			var frm_str = $("form[name=frm_change_status]").serialize();
			$.ajax
			({
				url : base_url + "/request/leads/changestatus",
				data : frm_str,
				dataType : "Json",
				type : "POST",
				success : function(response){
					if(response.is_error == 0)
					{ 
						leads_module.DisplayListLeads();
						$('#ChangeStatusModel').modal('toggle');
					}
				}
			});
		},
		SaveAddLeadResult : function(){
			var base_url = $('#BASE_URL').val();
			var frm_str = $("form[name=frm_add_result]").serialize();
			$.ajax
			({
				url : base_url + "/request/leads/addleadresult",
				data : frm_str,
				dataType : "Json",
				type : "POST",
				success : function(response){
					if(response.is_error == 0)
					{ 
						leads_module.DisplayListLeadResults();
                                                leads_module.DisplayListLeads();
						$('#AddResultModel').modal('toggle');
					}
				}
			});
		},
		SaveAssignLeadTo : function(){
			var base_url = $('#BASE_URL').val();
			var frm_str = $("form[name=frm_lead_assign_to]").serialize(); 
			 $.ajax
		        ({
		            url : base_url + "/request/leads/assignto",
		            data : frm_str,
		            dataType : "Json",
		            type : "POST",
		            success : function(response){
		              if(response.is_error == 0)
		              { 
		            	  	leads_module.DisplayListLeads();
		            		$('#AssignLeadModel').modal('toggle');
		              }
		            }
		        });
		},
		DisplayNotesTab : function()
		{
			 var base_url = $('#BASE_URL').val();
			 var _token = $('input[name=_token]').val();
		      var cl_id = $('input[name=cl_id]').val();
	        var str_params ={cl_id : cl_id , _token : _token};
	         $.ajax
	        ({
	            url : base_url + "/request/leads/displaynotestab",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $("#NotesManagement").html(response.display);
	            	  leads_module.DisplayListNotes();
	              }
	            }
	        });
		},
		DisplayAppointmentsTab : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
			var cl_id = $('input[name=cl_id]').val();
		    var display_type = $('input[name=display_type]').val();
	        var str_params ={cl_id : cl_id , display_type : display_type , _token : _token};
	        $.ajax
	        ({
	            url : base_url + "/request/leads/displayappointmentstab",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){ 
	              if(response.is_error == 0)
	              {
	            	  $("#AppointmentsManagement").html(response.display); 
	            	  var display_type = $('input[name=display_type]').val();
	            	  if(display_type == 'list')
            		  {
	            		 
		            	  
		              	$("a[id*=EDIT_APPT_]").on("click",leads_module.EditAppointmentInfo);
		            	$("a[id*=DELETE_APPT_]").on("click",leads_module.DeleteAppointmentInfo);
            		  }
	            	  else
            		  {
	            		 var path = response.calendar_path;
	            		  calendar_path = atob(path);
	          
	            			scheduler.init('scheduler_here',Date.now(),"week");
	            			scheduler.load(calendar_path);
            		  }
	            	  
	              }
	            }
	        });
		},
		DisplayActivitiesTab : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
			var cl_id = $('input[name=cl_id]').val();
			var str_params ={cl_id : cl_id , _token : _token};
			$.ajax
			({
				url : base_url + "/request/leads/displayactivitiestab",
				data : str_params,
				dataType : "Json",
				type : "POST",
				success : function(response){ 
					if(response.is_error == 0)
					{
						$("#ActivitiesManagement").html(response.display); 
						$("a[id*=EDIT_ACTIVITY_]").on("click",leads_module.EditActivityInfo);
						$("a[id*=DELETE_ACTIVITY_]").on("click",leads_module.DeleteActivityInfo);
					}
				}
			});
		},
		DisplayListLeadLogsTab : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
		    var cl_id = $('input[name=cl_id]').val();
	        var str_params ={cl_id : cl_id , _token : _token};
	        $.ajax
	        ({
	            url : base_url + "/request/leads/displayleadlogstab",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){ 
	              if(response.is_error == 0)
	              {
	            	  $("#LogsManagement").html(response.display); 
	            	  
	              }
	            }
	        });
		},
		DisplayFilesTab : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
		    var cl_id = $('input[name=cl_id]').val();
	        var str_params ={cl_id : cl_id , _token : _token};
	         $.ajax
	        ({
	            url : base_url + "/request/leads/displayfilestab",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $("#FilesManagement").html(response.display);
	            	   
	              }
	            }
	        });
		},
		DisplayLeadContactsTab : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
		    var cl_id = $('input[name=cl_id]').val();
		    var str_params ={cl_id : cl_id , _token : _token};
	         $.ajax
	        ({
	            url : base_url + "/request/leads/displayleadcontacts",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $("#ContactsManagement").html(response.display); 
		            	$("a[id*=EDIT_CONTACT_]").on("click",contacts_module.EditContactInfo);
		            	$("a[id*=DELETE_CONTACT_]").on("click",contacts_module.DeleteLeadContactInfo);
	              }
	            }
	        });
		},
		AddLeadContact : function(){
			var cl_id 		= $('input[name=lead_id]').val();
			var base_url 	= $('#BASE_URL').val(); 
			window.location.href = base_url + "/crm/leads/addcontacts/" + cl_id;
		},
		AddLeadAppointment : function(){
			var cl_id 		= $('input[name=lead_id]').val();
			var base_url 	= $('#BASE_URL').val(); 
			window.location.href = base_url + "/crm/leads/addappointment/" + cl_id;
		},
		AddLeadActivity : function(){
			var cl_id 		= $('input[name=lead_id]').val();
			var base_url 	= $('#BASE_URL').val(); 
			window.location.href = base_url + "/crm/leads/addactivity/" + cl_id;
		},
		DisplayListNotes : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
		    var cl_id = $("input[name=lead_id]").val();
		    var str_params ={cl_id : cl_id , _token : _token};
	         $.ajax
	        ({
	            url : base_url + "/request/leads/displaylistnotes",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $(".LstNotes").html(response.display);
	              }
	            }
	        });
		},
		SubmitLeadNote : function(){
			var base_url 	= $('#BASE_URL').val();
			var _token 		= $('input[name=_token]').val();
			var cl_id 		= $('input[name=cl_id]').val();
		    var lead_notes 	= $('textarea#LEAD_NOTES').val();
		    if(lead_notes.length == 0)
	    	{
		    	bootbox.alert("Please Add You Note Before Submit");
		    	return false;
	    	}
		    
		    
		    var str_params ={cl_id : cl_id , lead_notes : lead_notes , _token : _token};
		    $.ajax
	        ({
	            url : base_url + "/request/leads/saveleadnote",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  leads_module.DisplayListNotes();
	              }
	              else
            	  {
            	  	bootbox.alert(response.error_msg);
            	  }
	              $('textarea#LEAD_NOTES').val("");
	            }
	        });
		},
		UploadLeadFile : function(){
			var base_url 	= $('#BASE_URL').val(); 
			
			 var FormDataFields = $("form[id=FRM_LEAD_DROPZONE]");

 	        var data = new FormData();
 	        var index = 0;

 	        $.each($("input[type=file]"), function(i, obj) {
 	                var name = $(this).attr('name');
 	                $.each(obj.files,function(j,file){
 	                        data.append(name, file);
 	                })
 	        });

 	        FormDataFields.find('input').each(function(){
 	                data.append($(this).attr('name'), $(this).val() );
 	        });
 	        
 	       $.ajax
	        ({
	            url : base_url + "/request/leads/uploadleadfile",
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
	            	  leads_module.DisplayFilesTab();
	            	  $("#BTN_CLOSE").trigger('click');
	              }
	            }
	        });
			
		},
		DeleteLeadFile : function(){
			var lf_id = $(this).data('lf_id');
			bootbox.confirm("Are you sure you want to delete ?", function(result){
				//result
				if(result == true)
				{
					var base_url = $('#BASE_URL').val();
					var _token = $('input[name=_token]').val();
					var str_params ={lf_id : lf_id , _token : _token};
					$.ajax
					({
						url : base_url + "/request/leads/deleteleadfile",
						data : str_params,
						dataType : "Json",
						type : "POST",
						success : function(response){
							if(response.is_error == 0)
							{
								leads_module.DisplayListLeads();
							}
						}
					});
				}
			});
		},
		DeleteActivityInfo : function(){
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
				            url : base_url + "/request/leads/deleteleadactivity",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  leads_module.DisplayActivitiesTab();
				              }
				            }
				        });
				}
			});
		},
		SaveAppointmentInfo : function(){
			return leads_module.SaveAppointmentInfoSubmitHandler();
		},
		SaveAppointmentInfoSubmitHandler : function(){
			var SaveApptForm = $('#FORM_SAVE_APPOINTMENT');
	         var error3 = $('.alert-danger', SaveApptForm);
	         var success3 = $('.alert-success', SaveApptForm);

	         SaveApptForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 fk_lead_id : {
	                     required: true
	                 },
	                 ca_activity_subject : {
	                     required: true
	                 },
	                 fk_assigned_to : {
	                     required: true
	                 },
	                 ca_appointment_date : {
	                     required: true
	                 },
	                 ca_appointment_start_time : {
	                     required: true
	                 },
	                 ca_appointment_end_time : {
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
	    	       
	                var str_data = $("form[id=FORM_SAVE_APPOINTMENT]").serialize();

	    	       
	    	        const ca_appointment_description = $.desc_editor.getData();
	    	        const ca_appointment_results = $.result_editor.getData();
	    	        
	    	        str_data = str_data + "&ca_appointment_description=" + ca_appointment_description + "&ca_appointment_results=" + ca_appointment_results;
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/saveleadappointment",
	    	            data : str_data,
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  var cl_id = $("#FK_LEAD_ID").val();
	    	                 window.location.href = base_url + "/crm/leads/editform/" + cl_id;
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		},
		SaveLeadActivityInfo : function(){
			return leads_module.SaveLeadActivityInfoSubmitHandler();
		},
		SaveLeadActivityInfoSubmitHandler : function(){
			 var SaveActivityForm = $('#FORM_SAVE_ACTIVITY');
	         var error3 = $('.alert-danger', SaveActivityForm);
	         var success3 = $('.alert-success', SaveActivityForm);

	         SaveActivityForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 fk_lead_id : {
	                     required: true
	                 },
	                 fk_contact_id : {
	                     required: true
	                 },
	                 ca_activity_type : {
	                     required: true
	                 },
	                 ca_activity_purpose : {
	                	 required: true
	                 },
	                 ca_activity_subject : {
	                	 required: true
	                 },
	                 fk_owner_id : {
	                	 required: true
	                 },
	                 ca_activity_date : {
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
	    	       
	                var str_data = $("form[id=FORM_SAVE_ACTIVITY]").serialize();

	    	       
	    	        const ca_activity_description = $.desc_editor.getData();
	    	        const ca_activity_result = $.result_editor.getData();
	    	        
	    	        str_data = str_data + "&ca_activity_description=" + ca_activity_description + "&ca_activity_result=" + ca_activity_result;
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/saveleadactivity",
	    	            data : str_data,
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	  var cl_id = $("#FK_LEAD_ID").val();
	    	                 window.location.href = base_url + "/crm/leads/editform/" + cl_id;
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		},
		EditAppointmentInfo : function(){
			var ca_id = $(this).data("ca_id");
			var base_url = $("#BASE_URL").val();
			window.location.href = base_url + "/crm/appointments/editform/" + ca_id;
		},
		DeleteAppointmentInfo : function(){
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
				            url : base_url + "/request/leads/deleteleadappointment",
				            data : str_params,
				            dataType : "Json",
				            type : "POST",
				            success : function(response){
				              if(response.is_error == 0)
				              {
				            	  leads_module.DisplayAppointmentsTab();
				              }
				            }
				        });
				}
			});
		},
		GetServiceDropdown : function(){
			var sc_id = $(this).val();
			var base_url = $("#BASE_URL").val();
			var _token = $("input[name=_token]").val();
			
			var str_params = { sc_id : sc_id , _token : _token };
			
			$.ajax
	        ({
	            url : base_url + "/request/request/general/getdropdown/services",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	            	$(".ServicesDropdown").html(response.html);
	            	$("#CS_SERVICE").select2();
	            }
	        })
		},
		InsertLeadItems : function(){
			var cl_id = $("input[name=cl_id]").val();
			var sc_id = $("#CS_SERVICE_CATEGORY").val();
			 var ss_id = $("#CS_SERVICE").val();
			 var _token = $("input[name=_token]").val();
			 var params = { cl_id : cl_id , sc_id : sc_id , ss_id : ss_id  , _token : _token };
			 var base_url = $('#BASE_URL').val();
			 var str_params = $.param(params);
			 $.ajax
		        ({
		            url : base_url + "/request/leads/insertleaditems",
		            data : str_params,
		            dataType : "Json",
		            type : "POST",
		            success : function(response){
		              if(response.is_error == 0)
		              {
		            	  window.location.href = base_url + "/crm/leads/leaditemconfigurations/" + response.ci_id;
		              }
		            }
		        });
		},
		SaveLeadItemsInfo : function(){
			return leads_module.SaveLeadItemsInfoSubmitHandler();
		},
		SaveLeadItemsInfoSubmitHandler : function(){
			 var SaveLeaditemsForm = $('#FORM_SAVE_LEADITEMS');
	         var error3 = $('.alert-danger', SaveLeaditemsForm);
	         var success3 = $('.alert-success', SaveLeaditemsForm);

	         SaveLeaditemsForm.validate({
	             errorElement: 'span', //default input error message container
	             errorClass: 'help-block help-block-error', // default input error message class
	             focusInvalid: false, // do not focus the last invalid input
	             ignore: "", // validate all fields including form hidden input
	             rules: {
	            	 fk_lead_id : {
	                     required: true
	                 },
	                 fk_category_id : {
	                     required: true
	                 },
	                 fk_item_id : {
	                     required: true
	                 },
	                 ci_start_date : {
	                	 required: true
	                 },
	                 ci_end_date : {
	                	 required: true
	                 },
	                 fk_owner_id : {
	                	 required: true
	                 },
	                 ca_activity_date : {
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
	    	       
	                var str_data = $("form[id=FORM_SAVE_LEADITEMS]").serialize();

	    	       
	    	        const cs_service_description = $.editor.getData(); 
	    	        
	    	        str_data = str_data + "&cs_service_description=" + cs_service_description;
	    	         $.ajax
	    	        ({
	    	            url : base_url + "/request/leads/saveleaditeminfo",
	    	            data : str_data,
	    	            method : 'post', 
	    	            dataType : "json",
	    	            beforeSend : function(){
	    	            },
	    	            success : function(response){
	    	              if(response.is_error == 0)
	    	              {
	    	            	   
	    	                 window.location.href = base_url + "/crm/leads/editform/" + response.lead_id;
	    	              }
	    	            }
	    	        });
	                
	             }

	         });
		},
		DisplayListItemsTab : function(){
			var base_url = $('#BASE_URL').val();
			var _token = $('input[name=_token]').val();
		    var cl_id = $('input[name=cl_id]').val();
		    var str_params ={cl_id : cl_id , _token : _token};
	         $.ajax
	        ({
	            url : base_url + "/request/leads/displayleadservicestab",
	            data : str_params,
	            dataType : "Json",
	            type : "POST",
	            success : function(response){
	              if(response.is_error == 0)
	              {
	            	  $("#ItemsManagement").html(response.display); 
		            	$("a[id*=EDIT_LITEM_]").on("click",leads_module.EditLeadItemInfo);
		            	$("a[id*=DELETE_LITEM_]").on("click",leads_module.DeleteLeadItemInfo);
	              }
	            }
	        });
		},
		EditLeadItemInfo : function(){
			var ci_id = $(this).data("ci_id");
			var base_url = $('#BASE_URL').val();
			 window.location.href = base_url + "/crm/leads/leaditemconfigurations/" + ci_id;
		},
		DeleteLeadItemInfo : function(){
			 var ci_id = $(this).data('ci_id');
				bootbox.confirm("Are you sure you want to delete ?", function(result){
					//result
					if(result == true)
					{
					      var base_url = $('#BASE_URL').val();
					      var _token = $('input[name=_token]').val();
					        var str_params ={ci_id : ci_id , _token : _token};
					         $.ajax
					        ({
					            url : base_url + "/request/leads/deleteleadserviceinfo",
					            data : str_params,
					            dataType : "Json",
					            type : "POST",
					            success : function(response){
					              if(response.is_error == 0)
					              {
					            	  leads_module.DisplayListItemsTab();
					              }
					            }
					        });
					}
				});
		},
		ConvertLeadsToAccounts : function(){
			 var cl_ids 		= $("input[name=cl_ids]").val();
			 var base_url 		= $('#BASE_URL').val();
		      var _token 		= $('input[name=_token]').val();
		        var str_params 	= { cl_ids : cl_ids , _token : _token };
		        var i = 0;
		        for(i = 0;i<100;i++)
	        	{
		        	$("#PROGRESSBAR").attr("aria-valuenow",i);
	        	}
		        
		         $.ajax
		        ({
		            url : base_url + "/request/leads/converttoaccounts",
		            data : str_params,
		            dataType : "Json",
		            type : "POST",
		            success : function(response){
			           window.location.href = base_url + "/crm/clients"
		            }
		        });
		}
};