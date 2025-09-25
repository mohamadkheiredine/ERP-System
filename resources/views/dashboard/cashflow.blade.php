<?php
/***********************************************************
 * cashflow.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 9/21/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/
?>

@extends('layouts.layout',['page_title' => "Cashflow Dashboard"])

@section('plugins')
    <!-- amCharts 5 -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/plugins/exporting.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/fonts/notosans-sc.js"></script>
    <script>
        const fmtMoney = (n) => (new Intl.NumberFormat(undefined,{style:'currency',currency:'USD',maximumFractionDigits:0}).format(n||0));
        const pct = (p) => (p>0?`+${(p*100).toFixed(1)}%`:`${(p*100).toFixed(1)}%`);

        // amCharts roots (to dispose on refresh)
        let rootTrend = null, rootBars = null;

        function disposeRoot(root){
            if(root && !root.isDisposed()){
                root.dispose();
            }
        }

        function buildTrendChart(seriesRows){
            disposeRoot(rootTrend);
            rootTrend = am5.Root.new("chartTrend");
            rootTrend.setThemes([am5themes_Animated.new(rootTrend)]);

            const chart = rootTrend.container.children.push(am5xy.XYChart.new(rootTrend, {
                layout: rootTrend.verticalLayout,
                wheelY: "panY", pinchZoomX: true
            }));

            // Data preparation
            const data = seriesRows.map(r => ({
                label: r.label,
                inflow: Number(r.inflow||0),
                outflow: Number(r.outflow||0),
                net: Number(r.net||0)
            }));

            const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(rootTrend, {
                categoryField: "label",
                renderer: am5xy.AxisRendererX.new(rootTrend, { minGridDistance: 30, cellStartLocation: 0.2, cellEndLocation: 0.8 })
            }));
            xAxis.data.setAll(data);

            const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(rootTrend, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(rootTrend, {})
            }));

            function makeLine(name, field, stroke){
                const series = chart.series.push(am5xy.LineSeries.new(rootTrend, {
                    name, xAxis, yAxis, valueYField: field, categoryXField: "label",
                    tooltip: am5.Tooltip.new(rootTrend, { labelText: "{name}: {valueY}" }),
                    tension: 0.6
                }));
                series.strokes.template.setAll({ strokeWidth: 2 });
                if(stroke) series.get("stroke").setAll(stroke);
                series.data.setAll(data);
                series.appear(1000);
                return series;
            }

            makeLine("Net", "net", { });
            makeLine("Inflow", "inflow", { });
            makeLine("Outflow", "outflow", { });

            const legend = chart.children.push(am5.Legend.new(rootTrend, { centerX: am5.percent(50), x: am5.percent(50) }));
            legend.data.setAll(chart.series.values);

            chart.appear(1000, 200);
            // Exporting
            am5plugins_exporting.Exporting.new(rootTrend, { menu: am5plugins_exporting.ExportingMenu.new(rootTrend, {}) });
        }

        function buildBarsChart(seriesRows){
            disposeRoot(rootBars);
            rootBars = am5.Root.new("chartBars");
            rootBars.setThemes([am5themes_Animated.new(rootBars)]);

            const chart = rootBars.container.children.push(am5xy.XYChart.new(rootBars, {
                layout: rootBars.verticalLayout
            }));

            // Aggregate totals for simple 2-bar comparison
            const totals = seriesRows.reduce((acc,r)=>({
                inflow: acc.inflow + Number(r.inflow||0),
                outflow: acc.outflow + Number(r.outflow||0)
            }), {inflow:0,outflow:0});

            const data = [
                { name: "Inflow", value: totals.inflow },
                { name: "Outflow", value: totals.outflow }
            ];

            const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(rootBars, {
                categoryField: "name",
                renderer: am5xy.AxisRendererX.new(rootBars, { minGridDistance: 30 })
            }));
            xAxis.data.setAll(data);

            const yAxis = chart.yAxes.push(am5xy.ValueAxis.new(rootBars, {
                min: 0,
                renderer: am5xy.AxisRendererY.new(rootBars, {})
            }));

            const series = chart.series.push(am5xy.ColumnSeries.new(rootBars, {
                xAxis, yAxis, valueYField: "value", categoryXField: "name",
                tooltip: am5.Tooltip.new(rootBars, { labelText: "{categoryX}: {valueY}" })
            }));
            series.columns.template.setAll({ cornerRadiusTL: 6, cornerRadiusTR: 6 });
            series.data.setAll(data);

            series.appear(800);
            chart.appear(800, 100);

            am5plugins_exporting.Exporting.new(rootBars, { menu: am5plugins_exporting.ExportingMenu.new(rootBars, {}) });
        }

        function renderKPIs(summary){
            $('#kpiInflow').text(fmtMoney(summary.inflow));
            $('#kpiOutflow').text(fmtMoney(summary.outflow));
            $('#kpiNet').text(fmtMoney(summary.net));

            const inDelta = pct(summary.inflow_delta_pct || 0);
            const outDelta= pct(summary.outflow_delta_pct || 0);
            $('#kpiInflowDelta').text(inDelta).toggleClass('up', summary.inflow_delta_pct>=0).toggleClass('down', summary.inflow_delta_pct<0);
            $('#kpiOutflowDelta').text(outDelta).toggleClass('down', summary.outflow_delta_pct>=0).toggleClass('up', summary.outflow_delta_pct<0);

            $('#kpiBestLabel').text(`Best trend: ${summary.best_label || '—'}`);
        }

        function renderBreakdown(rows){
            const $tb = $('#tblBreakdown tbody').empty();
            rows.forEach(r=>{
                const tr = `<tr>
      <td>${r.category}</td>
      <td class="text-end">${fmtMoney(r.inflow)}</td>
      <td class="text-end">${fmtMoney(r.outflow)}</td>
      <td class="text-end ${r.net>=0?'text-success':'text-danger'}">${fmtMoney(r.net)}</td>
      <td class="text-end">${((r.share||0)*100).toFixed(1)}%</td>
    </tr>`;
                $tb.append(tr);
            });
        }

        function toCSV(rows){
            return (rows||[]).map(r => r.map(v => `"${String(v).replaceAll('"','""')}"`).join(',')).join('\n');
        }

        function loadData(){
            const from = $('#dateFrom').val();
            const to = $('#dateTo').val();
            const groupBy = $('#groupBy').val();
            const account = $('#accountFilter').val();

            const params = new URLSearchParams({ from,to,groupBy,account });
            window.history.replaceState({}, '', `${location.pathname}?${params.toString()}`);

            $.ajax({
                url: "#",
                data: { from, to, groupBy, account },
                dataType: 'json',
                beforeSend: function(){
                    $('#btnRefresh').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Loading...');
                }
            }).done(function(res){
                renderKPIs(res.summary || {});
                const series = res.series || [];
                buildTrendChart(series);
                buildBarsChart(series);
                renderBreakdown(res.breakdown || []);
                $('#btnExportCsv').data('csv', toCSV(res.export_rows || []));
            }).fail(function(xhr){
                alert('Failed to load data. Please check the API.\n\n' + (xhr.responseText||''));
            }).always(function(){
                $('#btnRefresh').prop('disabled', false).html('<i class="bi bi-arrow-repeat me-1"></i>Refresh');
            });
        }

        $(function(){
            loadData();

            $('#btnRefresh, #groupBy, #accountFilter, #dateFrom, #dateTo').on('change click', loadData);

            $('#btnSortNet').on('click', function(){
                const rows = $('#tblBreakdown tbody tr').get();
                rows.sort((a,b)=>{
                    const na = Number($(a).children().eq(3).text().replace(/[^\d\-\.]/g,'')) || 0;
                    const nb = Number($(b).children().eq(3).text().replace(/[^\d\-\.]/g,'')) || 0;
                    return nb - na;
                });
                $('#tblBreakdown tbody').append(rows);
            });

            $('#btnResetSort').on('click', loadData);

            $('#btnExportCsv').on('click', function(e){
                e.preventDefault();
                const csv = $(this).data('csv') || '';
                const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url; a.download = `cashflow_${new Date().toISOString().slice(0,10)}.csv`;
                document.body.appendChild(a); a.click(); document.body.removeChild(a);
                URL.revokeObjectURL(url);
            });

            $('#btnPrint').on('click', function(e){
                e.preventDefault();
                window.print();
            });
        });
    </script>
@endsection
@section('themes')
    <link href="{{ url('admin/assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('admin/assets/assets/demo/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .kpi-card { border:0; border-radius:1rem; box-shadow:0 8px 24px rgba(0,0,0,.06); background:#fff; }
        .kpi-delta.up { color:#0f9d58; }
        .kpi-delta.down { color:#d93025; }
        .sticky-toolbar { position:sticky; top:0; z-index:1030; background:#f7f8fb; padding:1rem 0 .25rem; }
        .legend-dot{display:inline-block;width:.75rem;height:.75rem;border-radius:50%;margin-right:.4rem;vertical-align:middle;}
        /* amCharts containers */
        #chartTrend, #chartBars { width:100%; height:340px; }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Cashflow</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item DownloadContract" data-action_type="DOWNLOAD_CONTRACT" href="#">Download Contract</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid py-3 py-md-4">

                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h1 class="h3 mb-0">Cashflow — Best Trend</h1>
                        <small class="text-muted">Track inflows, outflows, and net cash to spot best-performing trends.</small>
                    </div>
                    <div class="text-end">
                        <a href="#" id="btnExportCsv" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-filetype-csv me-1"></i>Export CSV
                        </a>
                        <a href="#" id="btnPrint" class="btn btn-primary btn-sm">
                            <i class="bi bi-printer me-1"></i>Print
                        </a>
                    </div>
                </div>

                <!-- Toolbar -->
                <div class="sticky-toolbar">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-2">
                            <label class="form-label mb-1">From</label>
                            <input id="dateFrom" type="date" class="form-control" value="{{ request('from') ?? now()->subMonths(2)->startOfMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label mb-1">To</label>
                            <input id="dateTo" type="date" class="form-control" value="{{ request('to') ?? now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">Company / Account</label>
                            <select id="accountFilter" class="form-select">
                                <option value="">All accounts</option>
                                <option value="main">Main Operating</option>
                                <option value="payroll">Payroll</option>
                                <option value="merchant">Merchant</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label mb-1">Group By</label>
                            <select id="groupBy" class="form-select">
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month" selected>Month</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 text-md-end">
                            <button id="btnRefresh" class="btn btn-dark w-100">
                                <i class="bi bi-arrow-repeat me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- KPIs -->
                <div class="row g-3 my-3">
                    <div class="col-12 col-md-4">
                        <div class="card kpi-card p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">Total Inflow</small>
                                    <div class="h4 mb-0" id="kpiInflow">—</div>
                                </div>
                                <i class="bi bi-arrow-down-left-circle text-success fs-3"></i>
                            </div>
                            <div class="small mt-2"><span class="kpi-delta up" id="kpiInflowDelta">—</span> vs prev period</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card kpi-card p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">Total Outflow</small>
                                    <div class="h4 mb-0" id="kpiOutflow">—</div>
                                </div>
                                <i class="bi bi-arrow-up-right-circle text-danger fs-3"></i>
                            </div>
                            <div class="small mt-2"><span class="kpi-delta down" id="kpiOutflowDelta">—</span> vs prev period</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card kpi-card p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">Net Cash</small>
                                    <div class="h4 mb-0" id="kpiNet">—</div>
                                </div>
                                <i class="bi bi-cash-coin text-primary fs-3"></i>
                            </div>
                            <div class="small mt-2">
                                <span class="badge rounded-pill bg-success-subtle text-success" id="kpiBestLabel">Best trend: —</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts (amCharts containers are divs instead of canvas) -->
                <div class="row g-3">
                    <div class="col-12 col-lg-8">
                        <div class="card kpi-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Net Cash Trend</h6>
                                <div class="small text-muted">
                                    <span class="legend-dot" style="background:#0d6efd"></span>Net&nbsp;&nbsp;
                                    <span class="legend-dot" style="background:#198754"></span>In&nbsp;&nbsp;
                                    <span class="legend-dot" style="background:#dc3545"></span>Out
                                </div>
                            </div>
                            <div id="chartTrend" class="mt-3"></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="card kpi-card p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Inflow vs Outflow</h6>
                            </div>
                            <div id="chartBars" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Breakdown -->
                <div class="card kpi-card p-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Category Breakdown</h6>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-1" id="btnSortNet">Sort by Net</button>
                            <button class="btn btn-sm btn-outline-secondary" id="btnResetSort">Reset</button>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm align-middle" id="tblBreakdown">
                            <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Inflow</th>
                                <th class="text-end">Outflow</th>
                                <th class="text-end">Net</th>
                                <th class="text-end">Share</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <footer class="text-center text-muted small my-4">
                    <span></span>
                </footer>
            </div>
        </div>
    </div>


@endsection
