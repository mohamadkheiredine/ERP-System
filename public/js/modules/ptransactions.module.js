ptransactions_module = {
    DisplayListPayRollTransactions: function () {
        var base_url = $('input[name=base_url]').val();
        var _token = $('input[name=_token]').val()
        var page_number = $('input[name=page_number]').val();
        var general_search = $('input[name=general_search]').val();
        var pt_company_id = $('select[name=pt_company_id]').val();
        var pt_employee_id = $('select[name=pt_employee_id]').val();
        $.ajax
        ({
            url: base_url + "/request/ptransactions/displaylist",
            data: {
                _token: _token,
                page_number: page_number,
                general_search: general_search,
                pt_company_id: pt_company_id,
                pt_employee_id: pt_employee_id
            },
            method: 'get',
            dataType: "json",
            beforeSend: function () {
            },
            success: function (response) {
                $('.LstPayRollTransactions').html(response.display);
                $('.group-checkable').change(function () {
                    var set = $('table').find('tbody > tr > td:nth-child(1) input[type="checkbox"]');
                    var checked = $(this).prop("checked");
                    $(set).each(function () {
                        $(this).prop("checked", checked);
                    });
                    $.uniform.update(set);
                });
                if (response.total_pages > 0) {
                    $.pagination = $('#PayRollTransactionsPagination').twbsPagination({
                        totalPages: response.total_pages,
                        visiblePages: 7,
                        onPageClick: function (event, page) {
                            $('input[name=page_number]').val(page);
                            ptransactions_module.DisplayListPayRollTransactions();
                        }
                    });
                }

            }
        });

    },
    PayPayRollTransaction : function() {
        var base_url = $('input[name=base_url]').val();
        var _token = $('input[name=_token]').val()
        var pt_id = $(this).data('pt_id');

        bootbox.confirm('Are you sure that this payslip is paied', function (result){
            if(result == true)
            {
                $.ajax
                ({
                    url: base_url + "/request/ptransactions/payemployeepayroll",
                    data: {
                        _token: _token,
                        pt_id: pt_id
                    },
                    method: 'post',
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (response) {
                        ptransactions_module.DisplayListPayRollTransactions();
                    }
                });
            }
        });


    }
}
