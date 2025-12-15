@extends('layouts.layout', ['page_title' => 'Accounting Management'])

@section('themes')
    <style>
        th {
            text-align: center;
            cursor: pointer;
            vertical-align: middle;
        }
        td {
            vertical-align: middle;
        }
        .text-right {
            text-align: right;
        }
    </style>
@endsection
@section('plugins')
    <script type="text/javascript">
        $(function(){
            $('#BTN_FILTER').on('click',function(){
                let _token = $('input[name=_token]').val();
                let params =  {_token : _token };
                let base_url = $("#BASE_URL").val();
                $.ajax({
                    url: base_url + "/accounting/reports/displaylistbalancesheet",
                    data: {
                        _token: _token
                    },
                    method: "get",
                    dataType: "json",
                    beforeSend: function () {},
                    success: function (response) {
                        $(".LstBalanceSheetResults").html(response.display);
                    },
                });

            })
        })
    </script>
@endsection
@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                Balance Sheet (As of {{ $year }})
            </h3>

            {{-- ACTIONS --}}
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button"
                            class="btn btn-danger dropdown-toggle"
                            data-bs-toggle="dropdown">
                        Action
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item"
                               href="{{ url('accounts/balancesheet/download?type=pdf&year='.$year) }}">
                                Export PDF
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ url('accounts/balancesheet/download?type=csv&year='.$year) }}">
                                Export CSV
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- YEAR FILTER --}}
            <form method="GET" class="row mb-3">
                <div class="col-md-3">
                    <label>Year</label>
                    <input type="number"
                           name="year"
                           class="form-control"
                           value="{{ $year }}"
                           min="2000"
                           max="{{ date('Y') }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button class="btn btn-primary w-100">
                        Filter
                    </button>
                </div>
            </form>

            {{-- BALANCE SHEET TABLE --}}
            <div class="table-responsive LstBalanceSheetResults">
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
            </div>

        </div>
    </div>
@endsection
