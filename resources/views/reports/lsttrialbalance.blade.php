<?php
/***********************************************************
 * lsttrialbalance.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 12/12/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-light">
    <tr>
        <th>Account Code</th>
        <th>Account Name</th>
        <th>Opening Balance</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Closing Balance</th>
    </tr>
    </thead>

    <tbody>
    @php
        $total_opening = 0;
        $total_debit   = 0;
        $total_credit  = 0;
        $total_closing = 0;
    @endphp

    @foreach($lst_trial_balance as $row)
        @php
            $closing = $row->opening_balance + ($row->period_debits - $row->period_credits);

            $total_opening += $row->opening_balance;
            $total_debit   += $row->period_debits;
            $total_credit  += $row->period_credits;
            $total_closing += $closing;
        @endphp

        <tr>
            <td class="text-center">{{ $row->account_code }}</td>
            <td>{{ $row->account_name }}</td>
            <td class="text-end">{{ number_format($row->opening_balance, 2) }}</td>
            <td class="text-end">{{ number_format($row->period_debits, 2) }}</td>
            <td class="text-end">{{ number_format($row->period_credits, 2) }}</td>
            <td class="text-end">{{ number_format($closing, 2) }}</td>
        </tr>
    @endforeach
    </tbody>

    {{-- TOTALS --}}
    <tfoot class="table-secondary fw-bold">
    <tr>
        <td colspan="2" class="text-center">TOTAL</td>
        <td class="text-end">{{ number_format($total_opening, 2) }}</td>
        <td class="text-end">{{ number_format($total_debit, 2) }}</td>
        <td class="text-end">{{ number_format($total_credit, 2) }}</td>
        <td class="text-end">{{ number_format($total_closing, 2) }}</td>
    </tr>
    </tfoot>
</table>
