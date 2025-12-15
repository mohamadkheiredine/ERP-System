<table class="table table-bordered table-striped align-middle">
    <thead class="table-light">
    <tr>
        <th width="10%">Account Code</th>
        <th width="30%">Account Name</th>
        <th width="15%">Opening Balance</th>
        <th width="15%">Year Movement</th>
        <th width="15%">Final Balance</th>
    </tr>
    </thead>

    <tbody>
    @php
        $total_opening = 0;
        $total_movement = 0;
        $total_final = 0;
    @endphp

    @foreach($balance_sheet as $row)
        @php
            $total_opening += $row->opening_balance;
            $total_movement += $row->yearly_movement;
            $total_final += $row->final_balance;
        @endphp

        <tr>
            <td class="text-center">{{ $row->account_code }}</td>
            <td>{{ $row->account_name }}</td>
            <td class="text-right">
                {{ number_format($row->opening_balance, 2) }}
            </td>
            <td class="text-right">
                {{ number_format($row->yearly_movement, 2) }}
            </td>
            <td class="text-right fw-bold">
                {{ number_format($row->final_balance, 2) }}
            </td>
        </tr>
    @endforeach
    </tbody>

    {{-- TOTALS --}}
    <tfoot class="table-secondary fw-bold">
    <tr>
        <td colspan="2" class="text-center">TOTAL</td>
        <td class="text-right">{{ number_format($total_opening, 2) }}</td>
        <td class="text-right">{{ number_format($total_movement, 2) }}</td>
        <td class="text-right">{{ number_format($total_final, 2) }}</td>
    </tr>
    </tfoot>
</table>
