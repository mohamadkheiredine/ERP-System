<?php
/***********************************************************
lstsalarydetails
Product : titanerp
Version : 1.0
Release : 1
Date Created : Sep 21, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>
@foreach($lst_salarydetails  as $index => $details_info)
    <tr  class="odd gradeX" data-pd_id="{{ $details_info->pd_id }}">
        <td><input type="checkbox" name="ck_pd_{{ $details_info->pd_id }}" id="CK_PD_{{ $details_info->pd_id }}" class="checkboxes" value="{{ $details_info->pd_id }}" /></td>
        <td>{{ $details_info->pd_id }}</td>
        <td>{{ $details_info->Company->cd_company_name }}</td>
        <td>{{ $details_info->Employee->u_fullname }}</td>
        <td>{{ $details_info->pd_basic_salary }} <b>{{ $details_info->Currency->cc_currency_code }}</b></td>
        <td>{{ $details_info->pd_total_comissions }} <b>{{ $details_info->Currency->cc_currency_code }}</b></td>
        <td>{{ $details_info->pd_deductions }}&nbsp;<b>{{ $details_info->Currency->cc_currency_code }}</b></td>
        <td>{{ $details_info->pd_allowances }}&nbsp;<b>{{ $details_info->Currency->cc_currency_code }}</b></td>
        <td>{{ $details_info->pd_effective_date }}</td>
        <td><a href="#" data-pd_id="{{ $details_info->pd_id }}" id="EDIT_SLRDETAILS_{{ $details_info->pd_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" data-pd_id="{{ $details_info->pd_id }}"  id="DELETE_SLRDETAILS_{{ $details_info->pd_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach

