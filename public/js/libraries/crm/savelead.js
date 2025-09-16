/**
 *
 */
$(function(){

        if($('#CL_LEAD_DESCRIPTION').length > 0)
        {
	 ClassicEditor
            .create( document.querySelector( '#CL_LEAD_DESCRIPTION' ) )
            .then( newEditor => {
               $.editor = newEditor;
           } )
            .catch( error => {
                console.error( error );
            } );
        }

	 $('#CL_AVATAR_PIC').on('change', function () {
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

                            $("#AVATAR_PIC").attr('src', e.target.result);
                        }
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[i]);
                    }

                }
            }
        });
	 $("#BTN_SAVE_LEAD").on("click",leads_module.SaveLeadsInfo);
	 $("#CL_MOBILE").on('blur',leads_module.DisplayExistingRecordLead);
	 var cl_id = $("input[name=cl_id]").val();

	 if(cl_id > 0)
	 {
		 leads_module.DisplayNotesTab();
		 $("#LnkTabFiles").on("click",leads_module.DisplayFilesTab);
		 $("#LnkTabContacts").on("click",leads_module.DisplayLeadContactsTab);
		 $("#LnkTabActivities").on("click",leads_module.DisplayActivitiesTab);
		 $("#LnkTabAppointments").on("click",leads_module.DisplayAppointmentsTab);
		 $("#TABITEMS").on("click",leads_module.DisplayListItemsTab);
		 $("#LnkTabLogs").on("click",leads_module.DisplayListLeadLogsTab);
		 $("#NotesManagement").on('click',"#BTN_SUBMIT_COMMENT",leads_module.SubmitLeadNote);
		 $("#FilesManagement").on('click',".DeleteFile",leads_module.DeleteLeadFile);
		 $("#BTN_UPLOAD_FILE").on('click',leads_module.UploadLeadFile);
		 $("#BTN_ADD_CONTACT").on('click',leads_module.AddLeadContact);
		 $("#BTN_ADD_ACTIVITY").on('click',leads_module.AddLeadActivity);
		 $("#CS_SERVICE_CATEGORY").on('change',leads_module.GetServiceDropdown);
		 $("#BTN_ADD_APPOINTMENTS").on('click',leads_module.AddLeadAppointment);
		 $("#BTN_INSERT_ITEMS").on('click',leads_module.InsertLeadItems);
		 $("#CL_TYPE_ITEMS").on('change',function(){
			 var type =   $("#CL_TYPE_ITEMS").val();
			if(type == 1)
			{
				if(!$("#TABITEMS").hasClass("disabled"))
				{
					$("#TABITEMS").addClass("disabled");
				}

			}
			else
			{
				$("#TABITEMS").removeClass("disabled");
			}
		 });
		 $("#SWITCH_VIEW_LIST,#SWITCH_VIEW_CALENDAR").on('click',function(){
			 var type = $(this).data('type');
			 if(type == 'list')
			 {
				 $("#SEARCH_BLOCK").css({display : ""});
			 }
			 else
			 {
				 $("#SEARCH_BLOCK").css({display : "none"});
			 }
			 $("#DISPLAY_TYPE").val(type);
			 leads_module.DisplayAppointmentsTab();
		 });
		 Dropzone.options.mDropzoneTwo = {
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 10,
            maxFilesize: 10, // MB
            addRemoveLinks: true,
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            }
        };

		 var type =   $("#CL_TYPE_ITEMS").val();
		if(type == 1)
		{
			if(!$("#TABITEMS").hasClass("disabled"))
			{
				$("#TABITEMS").addClass("disabled");
			}

		}
		else
		{
			 leads_module.DisplayListItemsTab();
		}
	 }
    $("#CL_AREA").on("change",leads_module.getlistofregions);


         $(document).on("keydown", function (e) {
            if (e.key === "Enter") {
                let $current = $(":focus");
                let tabIndex = $current.attr("tabindex");

                if (tabIndex !== undefined) {
                    let nextTabIndex = parseInt(tabIndex) + 1;
                    let $nextElement = $("[tabindex='" + nextTabIndex + "']");

                    if ($nextElement.length) {
                        e.preventDefault(); // Prevent default Enter behavior
                        $nextElement.focus();
                    }
                }
            }
        });

})
