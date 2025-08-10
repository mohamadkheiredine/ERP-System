<?php


?>
@foreach($lst_expenses as $index => $expense_info)
    <tr class="odd gradeX" data-ac_id="{{ $expense_info->ac_id }}">
        <td><input type="checkbox" name="ck_ac_{{ $expense_info->ac_id }}" id="CK_AC_{{ $expense_info->ac_id }}" class="checkboxes" value="{{ $expense_info->ac_id }}" /></td>
        <td>{{ $expense_info->ac_id }}</td>
        <td>{{ $expense_info->Category ? $expense_info->Category->ec_name : "N/A" }}</td>
        <td>{{ $expense_info->Employee ? $expense_info->Employee->u_fullname : "N/A" }}</td>
        <td>{{ $expense_info->ac_expense_date }}</b></td>
        <td>{{ $expense_info->ac_amount }}&nbsp;<b>{{ $expense_info->Currency->cc_currency_code }}</b></td>
        <td><a href="#"  id="EDIT_EXPENSE_{{ $expense_info->ac_id }}" ><i class="fas fa-edit" height="16"></i></a></td>
        <td><a href="#" id="DELETE_EXPENSE_{{ $expense_info->ac_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a></td>
    </tr>
@endforeach
