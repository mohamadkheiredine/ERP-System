


$(function(){
	$('#U_ROLE').select2({ placeholder: "Select a user role" });
	$('#U_USER_TYPE').select2({  placeholder: "Select a user type" });
	$('#U_JOB_ROLE_ID').select2({  placeholder: "Select a job role" });
	$('#U_JOB_TITLE_ID').select2({  placeholder: "Select a job title" });
	$('#U_DEPARTMENT_ID').select2({  placeholder: "Select a department" });
	$('#U_EMPLOYEE_TYPE').select2({  placeholder: "Select a Employee Type" });
	$('#FK_COMPANY_ID').select2({  placeholder: "Select a Company" });
	$('#FK_WAREHOUSE_ID').select2({  placeholder: "Select a Warehouse" });
	$('select').select2();


        new tempusDominus.TempusDominus(document.getElementById('U_DATE_BIRTH'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true,
			      clock: true,
			      hours: true,
			      minutes: true,
			      seconds: true,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "MM/dd/yyyy"

		 }
	});

           new tempusDominus.TempusDominus(document.getElementById('U_EMPLOYMENT_DATE'),{
		 display: {
			  components: {
			      calendar: true,
			      date: true,
			      month: true,
			      year: true,
			      decades: true,
			      clock: true,
			      hours: true,
			      minutes: true,
			      seconds: true,
			      useTwentyfourHour: undefined
			    }
		 },
		 localization: {
			 format : "MM/dd/yyyy"

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

	                        $("#PROFILE_PIC").attr('src', e.target.result);
	                    }
	                    image_holder.show();
	                    reader.readAsDataURL($(this)[0].files[i]);
	                }

	            }
	        }
	    });
	  $("#BTN_SAVE_USER").on("click",users_module.SaveUserInfo);
})
