<?php
/***********************************************************
 * lstpayrolltransactions.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/20/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


?>


@foreach($lst_transactions  as $index => $transaction_info)
    <tr  class="odd gradeX" data-pt_id="{{ $transaction_info->pt_id }}">
        <td><input type="checkbox" name="ck_pt_{{ $transaction_info->pt_id }}" id="CK_PT_{{ $transaction_info->pt_id }}" class="checkboxes" value="{{ $transaction_info->pt_id }}" /></td>
        <td>{{ $transaction_info->pt_id }}</td>
        <td>{{ $transaction_info->Company->cd_company_name }}</td>
        <td>{{ $transaction_info->Employee->u_fullname }}</td>
        <td>{{ $transaction_info->pt_gross_salary + $transaction_info->ot_total_comissions }}</td>
        <td>{{ $transaction_info->pt_transaction_date }}</td>
        <td><a href="#" data-pt_id="{{ $transaction_info->pt_id }}" id="VIEW_TRANSACTION_{{ $transaction_info->pt_id }}" ><i class="fa-solid fa-eye"></i></a></td>
        <td><a href="#" style="{{ $transaction_info->pt_status == "paid" ? "display:none" : "" }}" data-pt_id="{{ $transaction_info->pt_id }}"  id="PAY_TRANSACTION_{{ $transaction_info->pt_id }}" ><i class="fa-solid fa-money-bill"></i></a></td>
    </tr>
@endforeach
