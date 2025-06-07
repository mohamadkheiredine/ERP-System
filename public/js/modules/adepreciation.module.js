adepreciation_module = {
    DisplayListDepreciations : function(){
        var base_url 	= $('input[name=base_url]').val();
        var _token 		= $('input[name=_token]').val()
        var page_number = $('input[name=page_number]').val();
        var general_search = $('input[name=general_search]').val();
        var ad_asset_id = $('select[name=ad_asset_id]').val();
        $.ajax
        ({
            url : base_url + "/request/assets/displaylistdep",
            data : { _token : _token , page_number : page_number , general_search : general_search , ad_asset_id : ad_asset_id },
            method : 'get',
            dataType : "json",
            beforeSend : function(){
            },
            success : function(response){
                $('.LstDepreciationGrid').html(response.display);
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
                    $.pagination = $('#AssetDepreciationPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=page_number]').val(page);
                            adepreciation_module.DisplayListDepreciations();
                        }
                    });
                }

            }
        });

    },
    SaveADepreciationInfo : function(){
        return adepreciation_module.SaveADepreciationSubmitHandler();
    },
    SaveADepreciationSubmitHandler : function(){
        var ADepForm = $('#FORM_SAVE_DEPRECIATION');
        var error3 = $('.alert-danger', ADepForm);
        var success3 = $('.alert-success', ADepForm);

        ADepForm.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            rules: {
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
                var str_params = $("#FORM_SAVE_DEPRECIATION").serialize();
                $.ajax
                ({
                    url : base_url + "/request/assets/savedepinfo",
                    data : str_params,
                    method : 'post',
                    dataType : "json",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            window.location.href = base_url + "/assets/depreciation";
                        }
                    }
                });
            }

        });
    },
    DeleteADepreciationData : function(){
        var ad_id = $(this).data('ad_id');
        bootbox.confirm("Are you sure you want to delete ?", function(result){
            //result
            if(result == true)
            {
                var base_url = $('#BASE_URL').val();
                var _token = $('input[name=_token]').val();
                var str_params ={ad_id : ad_id , _token : _token};
                $.ajax
                ({
                    url : base_url + "/request/assets/deletedepinfo",
                    data : str_params,
                    dataType : "Json",
                    type : "delete",
                    success : function(response){
                        if(response.is_error == 0)
                        {
                            adepreciation_module.DisplayListDepreciations();
                        }
                    }
                });
            }
        });
    },
    EditADepreciationInfo : function(){
        var ad_id = $(this).data('ad_id');
        var base_url = $("#BASE_URL").val();
        window.location.href = base_url + "/assets/depreciation/editform/" + ad_id;
    }
};
