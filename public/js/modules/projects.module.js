projects_module = {
    DisplayListProjects : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var page_number 		= $('input[name=page_number]').val();
        var fk_company_id 		= $('select[name=fk_company_id]').val();
        var fk_project_manager_id 		= $('select[name=fk_project_manager_id]').val();
        var pp_status_id 		= $('select[name=pp_status_id]').val();
        var fk_project_type_id 		= $('select[name=fk_project_type_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/displaylist",
            data : { _token : _token , page_number : page_number , fk_company_id : fk_company_id , fk_project_manager_id : fk_project_manager_id , pp_status_id : pp_status_id , fk_project_type_id : fk_project_type_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('#LstProjects').html(response.display);
                $.pagination = $('#ProjectsPagination').twbsPagination({
                    totalPages: response.total_pages,
                    visiblePages: 7,
                    onPageClick: function (event, page) {
                        $('input[name=page_number]').val(page);
                        projects_module.DisplayListProjects();
                    }
                });

            }
        });
    },
    DisplayProjectTeams : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var pp_id 		= $('input[name=pp_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/listteams",
            data : { _token : _token , pp_id : pp_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstProjectTeams').html(response.display);

            }
        });
    },
    DisplayProjectPhases : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var pp_id 		= $('input[name=pp_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/listphases",
            data : { _token : _token , pp_id : pp_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstProjectPhases').html(response.display);

            }
        });
    },
    DisplayProjectJobs : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var pp_id 		= $('input[name=pp_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/listjobs",
            data : { _token : _token , pp_id : pp_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstProjectJobs').html(response.display);

            }
        });
    },
    DisplayProjectTasks : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val();
        var pp_id 		= $('input[name=pp_id]').val();
        $.ajax
        ({
            url : base_url + "/request/projects/listtasks",
            data : { _token : _token , pp_id : pp_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstProjectTasks').html(response.display);

            }
        });
    },
    SubmitProjectTeamInfo : function(){
        return projects_module.SubmitProjectTeamSubmitHandler();
    },
    SubmitProjectTeamSubmitHandler : function(){
        var ProjectTeamsForm = $('#FRM_ADD_TEAM');
        var error3 = $('.alert-danger', ProjectTeamsForm);
        var success3 = $('.alert-success', ProjectTeamsForm);

        ProjectTeamsForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                pp_team_id : {
                    required: true
                },
                ptm_allocation_pct : {
                    required : true,
                    number :true
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
                var str_params = $("#FRM_ADD_TEAM").serialize();
                $.ajax
                ({
                    url : base_url + "/request/projects/linkprojectteam",
                    data : str_params,
                    method : 'put',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            bootbox.alert(response.error_msg);
                            projects_module.DisplayProjectTeams();
                            $('#modal_link_teams').modal('toggle');

                        }
                    }
                });
            }

        });
    },
    SaveProjectInfo : function(){
        return projects_module.SaveProjectSubmitHandler();
    },
    SaveProjectSubmitHandler : function(){
        var ProjectForm = $('#FORM_SAVE_PROJECT');
        var error3 = $('.alert-danger', ProjectForm);
        var success3 = $('.alert-success', ProjectForm);

        ProjectForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                pp_project_name : {
                    required: true
                },
                fk_project_type_id : {
                    required: true
                },
                fk_company_id : {
                    required: true
                },
                fk_project_manager_id : {
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
                var str_params = $("#FORM_SAVE_PROJECT").serialize();
                $.ajax
                ({
                    url : base_url + "/request/projects/saveinfo",
                    data : str_params,
                    method : 'post',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            window.location.href = base_url + "/pm/projects";
                        }
                    }
                });
            }

        });
    },
    DeleteProjectData : function(){
        var pp_id = $(this).data('pp_id');
        bootbox.confirm("Are you sure you want to delete ?", function(result){
            //result
            if(result == true)
            {
                var base_url = $('#BASE_URL').val();
                var _token = $('input[name=_token]').val();
                var str_params ={pp_id : pp_id , _token : _token};
                $.ajax
                ({
                    url : base_url + "/request/projects/deleteinfo",
                    data : str_params,
                    dataType : "Json",
                    type : "delete",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            projects_module.DisplayListProjects();
                        }
                    }
                });
            }
        });
    },
    EditProjectInfo : function(){
        var pp_id = $(this).data('pp_id');
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/pm/projects/editform/" + pp_id;
    }
};
