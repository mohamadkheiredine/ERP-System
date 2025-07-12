$(function(){
    employees_module.DisplayListEmployees();
    $("#COMPANY_ID").on('change',employees_module.DisplayListEmployees);
    $("#LstEmployees").on("click","a[id*=EDIT_EMPLOYEE_]",employees_module.EditEmployeeInfo);
    $("#LstEmployees").on("click","a[id*=DELETE_EMPLOYEE_]",employees_module.DeleteEmployeeInfo);

    $('input[name=general_search]').on('keyup',function(){
        //$('#UsersPagination').twbsPagination('destroy');
        $('input[name=page_number]').val(1);
        employees_module.DisplayListEmployees();
    });
})
