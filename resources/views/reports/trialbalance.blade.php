@extends('layouts.layout',['page_title' => "Accounting Management"])

@section('themes')
    <style>
        th {
            cursor: pointer;
            text-align: center;
        }
        td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('plugins')
    <script type="text/javascript">
        $(function(){
            $('#BTN_FILTER').on('click',function(){
                let _token = $('input[name=_token]').val();
                let date_from = $('input[name=date_from]').val();
                let date_to = $('input[name=date_to]').val();
                let params =  {_token : _token ,date_from : date_from , date_to : date_to };
                let base_url = $("#BASE_URL").val();
                $.ajax({
                    url: base_url + "/accounting/reports/displaylisttrialbalance",
                    data: {
                        _token: _token,
                        date_from: date_from,
                        date_to: date_to,
                    },
                    method: "get",
                    dataType: "json",
                    beforeSend: function () {},
                    success: function (response) {
                        $(".LstTrialBalanceResult").html(response.display);
                    },
                });

            })
        })
    </script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Trial Balance</h3>

            {{-- ACTIONS --}}
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button"
                            class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item"
                               href="{{ url('accounts/trial-balance/download?type=pdf&date_from=' . request('date_from') . '&date_to=' . request('date_to')) }}">
                                Export PDF
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ url('accounts/trial-balance/download?type=csv&date_from=' . request('date_from') . '&date_to=' . request('date_to')) }}">
                                Export CSV
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- DATE FILTER --}}
            <form method="GET" class="row mb-3">
                <div class="col-md-3">
                    <label>Date From</label>
                    <input type="date" name="date_from" class="form-control"
                           value="{{ request('date_from', date('Y-m-01')) }}">
                </div>
                <div class="col-md-3">
                    <label>Date To</label>
                    <input type="date" name="date_to" class="form-control"
                           value="{{ request('date_to', date('Y-m-t')) }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button class="btn btn-primary w-100" name="btn_filter" id="BTN_FILTER" type="button">
                        Filter
                    </button>
                </div>
            </form>

            {{-- TRIAL BALANCE TABLE --}}
            <div class="table-responsive LstTrialBalanceResult">
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
            </div>

            {{-- BALANCE CHECK --}}
            <div class="alert alert-{{ abs($total_debit - $total_credit) < 0.01 ? 'success' : 'danger' }} mt-3">
                <strong>Validation:</strong>
                Total Debit {{ number_format($total_debit,2) }}
                {{ abs($total_debit - $total_credit) < 0.01 ? '=' : '≠' }}
                Total Credit {{ number_format($total_credit,2) }}
            </div>

        </div>
    </div>
@endsection
