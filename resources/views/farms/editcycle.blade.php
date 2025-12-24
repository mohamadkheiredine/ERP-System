<?php
/***********************************************************
 * editcycle.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 11/22/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/
?>

{{-- resources/views/farm/cycle/edit_cycle.blade.php --}}
@extends('layouts.layout', ['page_title' => "Edit Farm Cycle"])

@section('themes')
    <style>
        th {
            white-space: nowrap;
        }
        .table-cycle input,
        .table-cycle select {
            min-width: 90px;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Farm Cycle - {{ $cycle->fc_code }}</h3>
            <div class="card-toolbar">
                <a href="{{ url()->previous() }}" class="btn btn-light">Back</a>
            </div>
        </div>

        <div class="card-body">
            <form id="FORM_SAVE_CYCLES" name="form_save_cycles" method="post" >
                @csrf
                <input type="hidden" name="fc_id" value="{{ $cycle->fc_id }}">
                {{-- HEADER --}}
                <div class="row mb-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cycle Code</label>
                            <input type="text" readonly name="fc_code" id="FC_CODE" class="form-control"
                                   value="{{ $cycle->fc_code }}" maxlength="50" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Farm Name</label>
                            <input type="text" name="fc_farm_name" id="FC_FARM_NAME" class="form-control"
                                   value="{{ $cycle->fc_farm_name }}" maxlength="150">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Bird Type</label><br/>
                            <select class="form-select"  name="fc_bird_type" id="FC_BIRD_TYPE"  data-control="select2" data-placeholder="Select Product"  tabindex="4">
                                <option value=""> -- Bird Type --</option>
                                @foreach ( $lst_bird_types as $key => $product_info )
                                    <option {{ $cycle->fc_bird_type == $product_info->p_id ? "selected" : "" }} value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Final Product</label><br/>
                            <select class="form-select"  name="fc_product_id" id="FC_PRODUCT_ID"  data-control="select2" data-placeholder="Select Final Bird"  tabindex="4">
                                <option value=""> -- Final Product --</option>
                                @foreach ( $lst_final_products as $key => $product_info )
                                    <option {{ $cycle->fc_product_id == $product_info->p_id ? "selected" : "" }} value="{{ $product_info->p_id }}">{{ $product_info->p_product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Unit</label><br/>
                            <select class="form-select" name="fc_quantity_unit" id="FC_QUANTITY_UNIT"  data-control="select2" data-placeholder="Select a Unit"  tabindex="4">
                                <option value=""> -- Unit --</option>
                                @foreach ( $lst_sys_units as $key => $unit_info )
                                    <option {{ $cycle->fc_quantity_unit == $unit_info->su_id ? "selected" : "" }} value="{{ $unit_info->su_id }}">{{ $unit_info->su_unit_code }}&nbsp;-&nbsp;{{ $unit_info->su_unit_label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Warehouse</label><br/>
                            <div class="col-md-12 WarehouseDropdown">
                                <select class="form-select" name="fc_warehouse_id" id="FC_WAREHOUSE_ID"  data-control="select2" data-placeholder="Select a Warehouse"  tabindex="4">
                                    <option value=""> -- Warehouse --</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                        <option {{ $cycle->fc_warehouse_id == $warehouse_info->w_id ? "selected" : "" }} value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <br />
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="fc_is_closed" id="FC_IS_CLOSED" value="1" />
                                <span class="form-check-label fw-semibold text-muted">
                                  Close Cycle
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Birds Number (Start)</label>
                            <input type="number" name="fc_birds_start" id="FC_BIRDS_START" class="form-control"
                                   value="{{ $cycle->fc_birds_start }}" min="0">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="text" name="fc_start_date" id="FC_START_DATE" class="form-control"
                                   value="{{ $cycle->fc_start_date }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="text" name="fc_end_date" id="FC_END_DATE" class="form-control"
                                   value="{{ $cycle->fc_end_date }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="fc_notes" id="FC_NOTES" class="form-control" rows="2">{{ $cycle->fc_notes }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_expenses">Expenses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pharmacy">Pharmacy Stock</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_logs">Logs</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_tab_expenses" role="tabpanel">
                            ...
                        </div>
                        <div class="tab-pane fade" id="kt_tab_pharmacy" role="tabpanel">
                            ...
                        </div>
                        <div class="tab-pane fade" id="kt_tab_logs" role="tabpanel">
                            ...
                        </div>
                    </div>
                </div>
                {{-- KPI + IMPORT/EXPORT --}}
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="fw-bold small text-muted">Total Mortality</div>
                                        <div id="KPI_TOTAL_MORTALITY" class="fs-5 fw-bold">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="fw-bold small text-muted">Final FCR</div>
                                        <div id="KPI_FINAL_FCR" class="fs-5 fw-bold">0.0000</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="fw-bold small text-muted">Avg Body Weight (g)</div>
                                        <div id="KPI_AVG_BW" class="fs-5 fw-bold">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-2">
                                        <div class="fw-bold small text-muted">Total Feed (kg)</div>
                                        <div id="KPI_TOTAL_FEED" class="fs-5 fw-bold">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Import / Export --}}
                    <div class="col-md-4 text-end">
                            <input type="file" name="import_file" id="IMPORT_FILE" class="form-control form-control-sm mb-1">
                            <button type="button" class="btn btn-sm btn-secondary">Import Excel</button>

                        <a href="#"
                           class="btn btn-sm btn-outline-primary">
                            Export to Excel
                        </a>
                    </div>
                </div>

                {{-- CHARTS --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header py-2">
                                <strong>Body Weight & FCR</strong>
                            </div>
                            <div class="card-body p-2">
                                <canvas id="chartBodyWeightFcr" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header py-2">
                                <strong>Feed Intake & Mortality</strong>
                            </div>
                            <div class="card-body p-2">
                                <canvas id="chartFeedMortality" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABLE + ADD ROW --}}
                <div class="row mb-2">
                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                        <h5>Daily Cycle Data</h5>
                        <button type="button" class="btn btn-sm btn-success" id="BTN_ADD_ROW">
                            + Add Day
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-cycle" id="CYCLE_TABLE">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Age (Days)</th>
                            <th>Feed Type</th>
                            <th>Feed Received (kg)</th>
                            <th>Feed Intake / Day (kg)</th>
                            <th>Feed in Stock (kg)</th>
                            <th>Mortality</th>
                            <th>Closing Birds</th>
                            <th>Body Weight / Bird (g)</th>
                            <th>Daily Intake (g/bird)</th>
                            <th>Cumulative Intake (g/bird)</th>
                            <th>FCR</th>
                            <th>Medicine</th>
                            <th>Water (L)</th>
                            <th>Diesel (L)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $rowIndex = 0; @endphp
                        @foreach($cycleDays as $day)
                            <tr data-row="{{ $rowIndex }}" class="cycle-row">
                                <td>{{ $rowIndex + 1 }}</td>
                                <td>
                                    <input type="date" name="days[{{ $rowIndex }}][date]" class="form-control form-control-sm fcd_date"
                                           value="{{ $day->fcd_date }}">
                                    <input type="hidden" name="days[{{ $rowIndex }}][id]" value="{{ $day->fcd_id }}">
                                </td>
                                <td>
                                    <input type="number" name="days[{{ $rowIndex }}][age_days]" class="form-control form-control-sm fcd_age_days"
                                           value="{{ $day->fcd_age_days }}">
                                </td>
                                <td>
                                    <input type="text" name="days[{{ $rowIndex }}][feed_type]" class="form-control form-control-sm fcd_feed_type"
                                           value="{{ $day->fcd_feed_type }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][feed_received_kg]" class="form-control form-control-sm fcd_feed_received"
                                           value="{{ $day->fcd_feed_received_kg }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][feed_intake_day_kg]" class="form-control form-control-sm fcd_feed_intake_day"
                                           value="{{ $day->fcd_feed_intake_day_kg }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][feed_in_stock_kg]" class="form-control form-control-sm fcd_feed_in_stock"
                                           value="{{ $day->fcd_feed_in_stock_kg }}">
                                </td>
                                <td>
                                    <input type="number" name="days[{{ $rowIndex }}][mortality]" class="form-control form-control-sm fcd_mortality"
                                           value="{{ $day->fcd_mortality }}">
                                </td>
                                <td>
                                    <input type="number" name="days[{{ $rowIndex }}][closing_birds]" class="form-control form-control-sm fcd_closing_birds"
                                           value="{{ $day->fcd_closing_birds }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][body_weight_g]" class="form-control form-control-sm fcd_body_weight"
                                           value="{{ $day->fcd_body_weight_g }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][daily_intake_g_per_bird]" class="form-control form-control-sm fcd_daily_intake"
                                           value="{{ $day->fcd_daily_intake_g_per_bird }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][cumulative_intake_g_per_bird]" class="form-control form-control-sm fcd_cumulative_intake"
                                           value="{{ $day->fcd_cumulative_intake_g_per_bird }}">
                                </td>
                                <td>
                                    <input type="number" step="0.0001" name="days[{{ $rowIndex }}][fcr]" class="form-control form-control-sm fcd_fcr"
                                           value="{{ $day->fcd_fcr }}">
                                </td>
                                <td>
                                    <input type="text" name="days[{{ $rowIndex }}][medicine]" class="form-control form-control-sm fcd_medicine"
                                           value="{{ $day->fcd_medicine }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][water_liters]" class="form-control form-control-sm fcd_water"
                                           value="{{ $day->fcd_water_liters }}">
                                </td>
                                <td>
                                    <input type="number" step="0.001" name="days[{{ $rowIndex }}][diesel_liters]" class="form-control form-control-sm fcd_diesel"
                                           value="{{ $day->fcd_diesel_liters }}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger BTN_DELETE_ROW">&times;</button>
                                </td>
                            </tr>
                            @php $rowIndex++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <button type="submit" id="BTN_SAVE_CYCLE" name="btn_save_cycle" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('plugins')
    <script src="{{ url('theme/style/src/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/modules/farmcycles.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/production/savefarmcycle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const THRESHOLDS = {
            mortalityPctWarn: 0.5,
            mortalityPctHigh: 1.0,
            fcrWarn: 1.8,
            fcrHigh: 2.1
        };

        let chartBodyWeightFcr = null;
        let chartFeedMortality = null;

        function toFloat(v) {
            v = parseFloat(v);
            return isNaN(v) ? 0 : v;
        }
        function parseDateInput(val) {
            if (!val) return null;
            const d = new Date(val);
            return isNaN(d.getTime()) ? null : d;
        }
        function diffDays(from, to) {
            if (!from || !to) return 0;
            const ms = to.getTime() - from.getTime();
            return Math.round(ms / (1000 * 60 * 60 * 24));
        }

        function recalcAges() {
            const startDate = parseDateInput(document.getElementById("FC_START_DATE")?.value);
            const rows = document.querySelectorAll("#CYCLE_TABLE tbody tr");
            rows.forEach(row => {
                const dateInput = row.querySelector(".fcd_date");
                const ageInput  = row.querySelector(".fcd_age_days");
                if (!dateInput || !ageInput) return;
                const d = parseDateInput(dateInput.value);
                if (startDate && d) {
                    const age = diffDays(startDate, d) + 1;
                    if (age > 0) ageInput.value = age;
                }
            });
        }

        function recalcCycleTable() {
            recalcAges();

            const tbody = document.querySelector("#CYCLE_TABLE tbody");
            const rows  = Array.from(tbody.querySelectorAll("tr"));
            const birdsStart = toFloat(document.getElementById("FC_BIRDS_START")?.value);

            let prevStockKG = 0;
            let prevClosingBirds = birdsStart;
            let prevCumulativeG = 0;

            let totalMortality = 0;
            let totalFeedKG = 0;
            let bwSum = 0;
            let bwCount = 0;
            let finalFcr = 0;

            let labels = [], dataBodyWeight = [], dataFcr = [], dataFeedIntake = [], dataMortality = [];

            rows.forEach((row, index) => {
                row.classList.remove("table-danger","table-warning","table-success");

                const feedReceived   = toFloat(row.querySelector(".fcd_feed_received")?.value);
                const feedIntakeKG   = toFloat(row.querySelector(".fcd_feed_intake_day")?.value);
                let closingBirds     = toFloat(row.querySelector(".fcd_closing_birds")?.value);
                const mortality      = toFloat(row.querySelector(".fcd_mortality")?.value);
                const bodyWeightG    = toFloat(row.querySelector(".fcd_body_weight")?.value);
                const dateVal        = row.querySelector(".fcd_date")?.value;

                if (index === 0) {
                    if (closingBirds <= 0 && birdsStart > 0) {
                        closingBirds = birdsStart - mortality;
                        row.querySelector(".fcd_closing_birds").value = closingBirds;
                    }
                } else {
                    if (mortality > 0) {
                        closingBirds = prevClosingBirds - mortality;
                        row.querySelector(".fcd_closing_birds").value = closingBirds;
                    }
                }

                let stockKG = prevStockKG + feedReceived - feedIntakeKG;
                if (!isFinite(stockKG)) stockKG = 0;
                row.querySelector(".fcd_feed_in_stock").value = stockKG.toFixed(3);
                prevStockKG = stockKG;

                let dailyG = 0;
                if (closingBirds > 0) {
                    dailyG = (feedIntakeKG * 1000) / closingBirds;
                }
                row.querySelector(".fcd_daily_intake").value = dailyG.toFixed(3);

                let cumG = prevCumulativeG + dailyG;
                row.querySelector(".fcd_cumulative_intake").value = cumG.toFixed(3);
                prevCumulativeG = cumG;

                let fcr = 0;
                if (bodyWeightG > 0) {
                    fcr = cumG / bodyWeightG;
                }
                row.querySelector(".fcd_fcr").value = fcr.toFixed(4);
                finalFcr = fcr;

                totalMortality += mortality;
                totalFeedKG += feedIntakeKG;
                if (bodyWeightG > 0) {
                    bwSum += bodyWeightG; bwCount++;
                }

                labels.push(dateVal || (index + 1));
                dataBodyWeight.push(bodyWeightG || null);
                dataFcr.push(fcr || null);
                dataFeedIntake.push(feedIntakeKG || null);
                dataMortality.push(mortality || null);

                const birdsBase = prevClosingBirds || birdsStart || 1;
                const mortalityPct = birdsBase > 0 ? (mortality * 100) / birdsBase : 0;
                let rowClass = "";
                if (mortalityPct >= THRESHOLDS.mortalityPctHigh || fcr >= THRESHOLDS.fcrHigh) {
                    rowClass = "table-danger";
                } else if (mortalityPct >= THRESHOLDS.mortalityPctWarn || fcr >= THRESHOLDS.fcrWarn) {
                    rowClass = "table-warning";
                } else if (bodyWeightG > 0 && fcr > 0) {
                    rowClass = "table-success";
                }
                if (rowClass) row.classList.add(rowClass);

                row.querySelector("td:first-child").innerText = index + 1;
                if (closingBirds > 0) prevClosingBirds = closingBirds;
            });

            const avgBw = bwCount > 0 ? (bwSum / bwCount) : 0;
            document.getElementById("KPI_TOTAL_MORTALITY").innerText = totalMortality.toString();
            document.getElementById("KPI_FINAL_FCR").innerText = finalFcr.toFixed(4);
            document.getElementById("KPI_AVG_BW").innerText = avgBw.toFixed(0);
            document.getElementById("KPI_TOTAL_FEED").innerText = totalFeedKG.toFixed(2);

            renderCharts(labels, dataBodyWeight, dataFcr, dataFeedIntake, dataMortality);
        }

        function renderCharts(labels, bw, fcr, feed, mort) {
            const ctx1 = document.getElementById("chartBodyWeightFcr");
            const ctx2 = document.getElementById("chartFeedMortality");
            if (!ctx1 || !ctx2) return;

            if (chartBodyWeightFcr) chartBodyWeightFcr.destroy();
            if (chartFeedMortality) chartFeedMortality.destroy();

            chartBodyWeightFcr = new Chart(ctx1, {
                type: "line",
                data: { labels, datasets: [
                        { label: "Body Weight (g)", data: bw, yAxisID: 'y1' },
                        { label: "FCR", data: fcr, yAxisID: 'y2' }
                    ]},
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    stacked: false,
                    scales: {
                        y1: { type: 'linear', position: 'left' },
                        y2: { type: 'linear', position: 'right' }
                    }
                }
            });

            chartFeedMortality = new Chart(ctx2, {
                type: "line",
                data: { labels, datasets: [
                        { label: "Feed Intake (kg)", data: feed, yAxisID: 'y1' },
                        { label: "Mortality (birds)", data: mort, yAxisID: 'y2' }
                    ]},
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    stacked: false,
                    scales: {
                        y1: { type: 'linear', position: 'left' },
                        y2: { type: 'linear', position: 'right' }
                    }
                }
            });
        }

        function addRow() {
            const tbody = document.querySelector("#CYCLE_TABLE tbody");
            const index = tbody.querySelectorAll("tr").length;

            const tr = document.createElement("tr");
            tr.classList.add("cycle-row");
            tr.setAttribute("data-row", index);
            tr.innerHTML = `
            <td>${index + 1}</td>
            <td>
                <input type="date" name="days[${index}][date]" class="form-control form-control-sm fcd_date">
                <input type="hidden" name="days[${index}][id]" value="">
            </td>
            <td><input type="number" name="days[${index}][age_days]" class="form-control form-control-sm fcd_age_days"></td>
            <td><input type="text" name="days[${index}][feed_type]" class="form-control form-control-sm fcd_feed_type"></td>
            <td><input type="number" step="0.001" name="days[${index}][feed_received_kg]" class="form-control form-control-sm fcd_feed_received" value="0"></td>
            <td><input type="number" step="0.001" name="days[${index}][feed_intake_day_kg]" class="form-control form-control-sm fcd_feed_intake_day" value="0"></td>
            <td><input type="number" step="0.001" name="days[${index}][feed_in_stock_kg]" class="form-control form-control-sm fcd_feed_in_stock" value="0"></td>
            <td><input type="number" name="days[${index}][mortality]" class="form-control form-control-sm fcd_mortality" value="0"></td>
            <td><input type="number" name="days[${index}][closing_birds]" class="form-control form-control-sm fcd_closing_birds" value="0"></td>
            <td><input type="number" step="0.001" name="days[${index}][body_weight_g]" class="form-control form-control-sm fcd_body_weight" value="0"></td>
            <td><input type="number" step="0.001" name="days[${index}][daily_intake_g_per_bird]" class="form-control form-control-sm fcd_daily_intake" value="0"></td>
            <td><input type="number" step="0.001" name="days[${index}][cumulative_intake_g_per_bird]" class="form-control form-control-sm fcd_cumulative_intake" value="0"></td>
            <td><input type="number" step="0.0001" name="days[${index}][fcr]" class="form-control form-control-sm fcd_fcr" value="0"></td>
            <td><input type="text" name="days[${index}][medicine]" class="form-control form-control-sm fcd_medicine"></td>
            <td><input type="number" step="0.001" name="days[${index}][water_liters]" class="form-control form-control-sm fcd_water" value="0"></td>
            <td><input type="number" step="0.001" name="days[${index}][diesel_liters]" class="form-control form-control-sm fcd_diesel" value="0"></td>
            <td><button type="button" class="btn btn-sm btn-danger BTN_DELETE_ROW">&times;</button></td>
        `;
            tbody.appendChild(tr);
            recalcCycleTable();
        }

        document.addEventListener("DOMContentLoaded", () => {
            recalcCycleTable();

            document.querySelector("#CYCLE_TABLE").addEventListener("input", (e) => {
                if (e.target.closest("tr")) recalcCycleTable();
            });

            const startDateEl = document.getElementById("FC_START_DATE");
            if (startDateEl) startDateEl.addEventListener("change", () => recalcCycleTable());

            const btnAddRow = document.getElementById("BTN_ADD_ROW");
            if (btnAddRow) btnAddRow.addEventListener("click", (e) => {
                e.preventDefault();
                addRow();
            });

            document.querySelector("#CYCLE_TABLE").addEventListener("click", (e) => {
                if (e.target.classList.contains("BTN_DELETE_ROW")) {
                    e.preventDefault();
                    e.target.closest("tr").remove();
                    recalcCycleTable();
                }
            });
        });
    </script>
@endsection

