brackets_module = {
    DisplayListBrackets : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val()
        var page_number = $('input[name=page_number]').val();
        var general_search = $('input[name=general_search]').val();
        var tb_company_id = $('select[name=tb_company_id]').val();
        $.ajax
        ({
            url : base_url + "/request/payroll/displaylisttaxbrackets",
            data : { _token : _token , page_number : page_number , general_search : general_search  , tb_company_id : tb_company_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstBracketsBody').html(response.display);
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
                    $.pagination = $('#BracketsPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=page_number]').val(page);
                            brackets_module.DisplayListBrackets();
                        }
                    });
                }

            }
        });

    },
    SaveBracketinfo : function(){
        return brackets_module.SaveBracketinfoSubmitHandler();
    },
    SaveBracketinfoSubmitHandler : function(){
        var BracketForm = $('#FORM_SAVE_BRACKET');
        var error3 = $('.alert-danger', BracketForm);
        var success3 = $('.alert-success', BracketForm);

        BracketForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
                tb_min_salary : {
                    required: true,
                    number:true
                },
                tb_max_salary : {
                    required: true,
                    number:true
                },
                tb_tax_rate : {
                    required: true,
                    number:true
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
                var str_params = $("#FORM_SAVE_BRACKET").serialize();
                $.ajax
                ({
                    url : base_url + "/request/payroll/savebracketsinfo",
                    data : str_params,
                    method : 'post',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            window.location.href = base_url + "/payrolls/taxbrackets";
                        }
                    }
                });
            }

        });
    },
    DeleteBracketData : function(){
        var tb_id = $(this).data('tb_id');
        bootbox.confirm("Are you sure you want to delete ?", function(result){
            //result
            if(result == true)
            {
                var base_url = $('#BASE_URL').val();
                var _token = $('input[name=_token]').val();
                var str_params ={ tb_id : tb_id , _token : _token };
                $.ajax
                ({
                    url : base_url + "/request/payroll/deletebracketinfo",
                    data : str_params,
                    dataType : "Json",
                    type : "delete",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            brackets_module.DisplayListBrackets();
                        }
                    }
                });
            }
        });
    },
    EditBracketInfo : function(){
        var tb_id = $(this).data('tb_id');
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/payrolls/taxbrackets/editform/" + tb_id;
    }
};
