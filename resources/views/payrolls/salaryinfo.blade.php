<?php
/***********************************************************
 * salaryinfo.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/19/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


<div class="row">
    <div class="col-md-12" style="text-align: center">
        <h3>Salary Details</h3>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped gy-7 gs-7">
        <thead>
        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
            <th></th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Basic Salary</b></td>
                <td>{{ $basic_salary  }}</td>
            </tr>
            <tr>
                <td><b>Bonus</b></td>
                <td>{{ $total_benefits  }}</td>
            </tr>
            <tr>
                <td><b>Deductions</b></td>
                <td>{{ $total_deductions  }}</td>
            </tr>
            <tr>
                <td><b>Comission</b></td>
                <td>{{ $total_comissions  }}</td>
            </tr>
            <tr>
                <td><b>Total</b></td>
                <td>{{ ($basic_salary + $total_benefits - $total_deductions + $total_comissions)  }}</td>
            </tr>
        </tbody>
    </table>
</div>
