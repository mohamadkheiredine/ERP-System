<?php
/***********************************************************
 * listexpenses.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 1/11/2026
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2026
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($lst_cycle_expenses as $index => $expense_info)
    <tr   class="odd gradeX" data-ce_id="{{ $expense_info->ce_id }}">
        <td><input type="checkbox" name="ck_ce_{{ $expense_info->ce_id }}" id="CK_CE_{{ $expense_info->ce_id }}" class="checkboxes" value="{{ $expense_info->ce_id }}" /></td>
        <td>{{ $expense_info->Voucher->pv_code }}</td>
        <td>{{ $expense_info->Voucher->AccountReceivable->aa_account }} - {{ $expense_info->Voucher->AccountReceivable->aa_account_label }}</td>
        <td>{{ $expense_info->Voucher->pv_payment_amount }}&nbsp;<b>$expense_info->currency->cc_currency_code }}</b></td>
        <td>{{ $expense_info->Voucher->pv_creation_date }}</td>
        <td style="width:2px;"> <a href="#"  data-ce_id="{{ $expense_info->ce_id }}"  id="DELETE_EXPENSE_{{ $expense_info->ce_id }}" ><i class="fa fa-minus-circle" aria-hidden="true" height="16" ></i></a> </td>
    </tr>
@endforeach
