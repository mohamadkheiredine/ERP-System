salarydetails_module = {
    DisplayListSalaryDetails : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val()
        var page_number = $('input[name=page_number]').val();
        var general_search = $('input[name=general_search]').val();
        var pd_company_id = $('select[name=pd_company_id]').val();
        var pd_employee_id = $('select[name=pd_employee_id]').val();
        $.ajax
        ({
            url : base_url + "/request/salarydetails/displaylist",
            data : { _token : _token , page_number : page_number , general_search : general_search , pd_company_id : pd_company_id , pd_employee_id : pd_employee_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstSallaryDetails').html(response.display);
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
                    $.pagination = $('#SalDetailsPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=page_number]').val(page);
                            salarydetails_module.DisplayListSalaryDetails();
                        }
                    });
                }

            }
        });

    },
    getEmployeeInfo : function(){
        var base_url = $('#BASE_URL').val();
        var _token = $('input[name=_token]').val();
        var pd_employee_id = $('select[name=pd_user_id]').val();
        $.ajax
        ({
            url : base_url + "/request/salarydetails/getemployeeinfo",
            data : { _token : _token ,  pd_employee_id : pd_employee_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $(".LstComissions").html(response.comissions);
                $(".SalaryInfo").html(response.salaryinfo);
                $("#PD_ALLOWANCES").val(response.total_benefits);
                $("#PD_DEDUCTION").val(response.total_deductions);
                $("#PD_TOTAL_COMISSION").val(response.total_comissions);
                $("#PD_BASIC_SALARY").val(response.basic_salary);

            }
        });
    },
    GeneratePayRollTransaction : function(){
        var base_url = $('#BASE_URL').val();
        var _token = $('input[name=_token]').val();
        var pd_employee_id = $('select[name=pd_user_id]').val();
        var pd_id = $('input[name=pd_id]').val();


        $.ajax
        ({
            url : base_url + "/request/salarydetails/generatepayrolltransaction",
            data : { _token : _token ,  pd_employee_id : pd_employee_id , pd_id : pd_id },
            method : 'post',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){


            }
        });

    },
    SaveSalDetailsInfo : function(){
        return salarydetails_module.SaveSalDetailsSubmitHandler();
    },
    SaveSalDetailsSubmitHandler : function(){
        var SalDetailsForm = $('#FORM_SAVE_SALDETAILS');
        var error3 = $('.alert-danger', SalDetailsForm);
        var success3 = $('.alert-success', SalDetailsForm);

        SalDetailsForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                pd_company_id : {
                    required: true
                },
                pd_basic_salary : {
                    required: true,
                    number : true
                },
                pd_allowances : {
                    number : true
                },
                pd_deductions : {
                    number : true
                },
                pd_total_comissions : {
                    number : true
                },
                pd_effective_date : {
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
                var str_params = $("#FORM_SAVE_SALDETAILS").serialize();
                $.ajax
                ({
                    url : base_url + "/request/salarydetails/saveinfo",
                    data : str_params,
                    method : 'post',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            window.location.href = base_url + "/payroll/salarydetails";
                        }
                    }
                });
            }

        });
    },
    DeleteSalDetailsData : function(){
        var pd_id = $(this).data('pd_id');
        bootbox.confirm("Are you sure you want to delete ?", function(result){
            //result
            if(result == true)
            {
                var base_url = $('#BASE_URL').val();
                var _token = $('input[name=_token]').val();
                var str_params ={pd_id : pd_id , _token : _token};
                $.ajax
                ({
                    url : base_url + "/request/salarydetails/deleteinfo",
                    data : str_params,
                    dataType : "Json",
                    type : "delete",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            salarydetails_module.DisplayListDedBen();
                        }
                    }
                });
            }
        });
    },
    EditPayRollDetailsInfo : function(){
        var pd_id = $(this).data('pd_id');
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/payroll/salarydetails/editform/" + pd_id;
    }
};
