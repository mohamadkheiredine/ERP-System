
projectjobs_module = {
    DisplayListProjectJobs : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var page_number 		= $('input[name=page_number]').val();
        var fk_project_id 		= $('select[name=fk_project_id]').val();
        var fk_phase_id 		= $('select[name=fk_phase_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/displaylistjobs",
            data : { _token : _token , fk_project_id : fk_project_id , fk_phase_id : fk_phase_id , page_number : page_number },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('#LstProjectJobs').html(response.display);
                $.pagination = $('#ProjectJobsPagination').twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        $('input[name=page_number]').val(page);
                        projectjobs_module.DisplayListProjectJobs();
                    }
                });

            }
        });
    },
    GenerateJobCode : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var fk_project_id 		= $('select[name=fk_project_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/generatejobcode",
            data : { _token : _token , fk_project_id : fk_project_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('#PJ_JOB_CODE').val(response.job_code);
            }
        });
    },
    SaveProjectJobInfo : function(){
        return projectjobs_module.SaveProjectJobSubmitHandler();
    },
    SaveProjectJobSubmitHandler : function(){
        var PhaseForm = $('#FORM_SAVE_JOB');
        var error3 = $('.alert-danger', PhaseForm);
        var success3 = $('.alert-success', PhaseForm);

        PhaseForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                fk_project_id : {
                    required: true
                },
                fk_phase_id : {
                    required: true
                },
                pj_owner_id : {
                    required: true
                },
                pj_job_name : {
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
                var str_params = $("#FORM_SAVE_JOB").serialize();
                $.ajax
                ({
                    url : base_url + "/request/projects/savejobsinfo",
                    data : str_params,
                    method : 'post',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            window.location.href = base_url + "/projects/jobs";
                        }
                    }
                });
            }

        });
    },
    DeleteProjectJobsData : function(){
        var pj_id = $(this).data('pj_id');
        bootbox.confirm("Are you sure you want to delete ?", function(result){
            //result
            if(result == true)
            {
                var base_url = $('#BASE_URL').val();
                var _token = $('input[name=_token]').val();
                var str_params ={pj_id : pj_id , _token : _token};
                $.ajax
                ({
                    url : base_url + "/request/projects/deletejobinfo",
                    data : str_params,
                    dataType : "Json",
                    type : "delete",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            projectjobs_module.DisplayListProjectJobs();
                        }
                    }
                });
            }
        });
    },
    EditProjectJobInfo : function(){
        var pj_id = $(this).data('pj_id');
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/projects/jobs/editform/" + pj_id;
    }
};
