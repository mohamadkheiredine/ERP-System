<?php
/***********************************************************
editplan.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jan 15, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

 ***********************************************************/



?>


@extends('layouts.layout',['page_title' => "Production Plan Management"])

@section('themes')
    <style>
        th{
            cursor: pointer;
        }
        #ModelPopUp{
            width:800px;
        }
        #minutes{
            margin-bottom: 0px !important;
            font-size:16px;
        }
        #seconds{
            margin-bottom: 0px !important;
            font-size:16px;
        }
        #hours{
            margin-bottom: 0px !important;
            font-size:16px;
        }
        #real_hours, #real_minutes, #real_seconds{
            margin-bottom: 0px !important;
            font-size:16px;
        }
    </style>
@endsection
@section('plugins')
    <script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ url('js/modules/productionplans.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/production/saveplaninfo.js') }}"></script>
    <script type="text/javascript">
        // Global variable to hold the interval ID for countdown timer
        let my_int;
        // Global variable to hold the interval ID for real duration timer
        let real_duration_int;

        /**
         * Pads a number with a leading zero if it is less than 10.
         * @param {number|string} val - The number to pad.
         * @returns {string} The padded string.
         */
        function pad(val) {
            let valString = val + "";
            if (valString.length < 2) {
                return "0" + valString;
            } else {
                return valString;
            }
        }

        /**
         * Decrements the timer by one second and updates the labels.
         * Stops the timer when it reaches 00:00:00.
         * @param {HTMLElement} HoursLabel - The element displaying hours.
         * @param {HTMLElement} minutesLabel - The element displaying minutes.
         * @param {HTMLElement} secondsLabel - The element displaying seconds.
         */
        function setTime(HoursLabel, minutesLabel, secondsLabel) {
            // Get the current time values from the DOM elements
            let hours   = parseInt(HoursLabel.innerHTML);
            let minutes = parseInt(minutesLabel.innerHTML);
            let seconds = parseInt(secondsLabel.innerHTML);

            // 🛑 Check if the timer has reached 00:00:00
            if (hours === 0 && minutes === 0 && seconds === 0) {
                clearInterval(my_int);
                // Optional: Alert or log that the timer is finished
                console.log("Countdown finished!");
                return;
            }

            // --- Decrement Logic ---

            if (seconds === 0) {
                // Seconds reset to 59, and check if we need to decrement minutes
                seconds = 59;
                if (minutes === 0) {
                    // Minutes reset to 59, and decrement hours
                    minutes = 59;
                    if (hours > 0) {
                        hours--;
                    }
                } else {
                    // Just decrement minutes
                    minutes--;
                }
            } else {
                // Just decrement seconds
                seconds--;
            }

            // Update the DOM elements with the new, padded time
            HoursLabel.innerHTML = pad(hours);
            minutesLabel.innerHTML = pad(minutes);
            secondsLabel.innerHTML = pad(seconds);
        }

        /**
         * Initializes and starts the countdown timer.
         * Assumes the HTML elements are already set to a starting time (e.g., 01:00:00).
         */
        function set_timer() {
            // Re-declare the labels by fetching them from the DOM
            const HoursLabel = document.getElementById("hours");
            const minutesLabel = document.getElementById("minutes");
            const secondsLabel = document.getElementById("seconds");

            // Clear any existing interval before starting a new one
            if (my_int) {
                clearInterval(my_int);
            }

            // Set up the interval to call setTime every 1000 milliseconds (1 second)
            my_int = setInterval(function() { setTime(HoursLabel, minutesLabel, secondsLabel) }, 1000);
        }

        /**
         * Stops the running countdown timer.
         */
        function stop_timer() {
            clearInterval(my_int);
        }

        /**
         * Increments the real duration timer by one second and updates the labels.
         * @param {HTMLElement} realHoursLabel - The element displaying real duration hours.
         * @param {HTMLElement} realMinutesLabel - The element displaying real duration minutes.
         * @param {HTMLElement} realSecondsLabel - The element displaying real duration seconds.
         */
        function setRealDuration(realHoursLabel, realMinutesLabel, realSecondsLabel) {
            // Get the current time values from the DOM elements
            let hours   = parseInt(realHoursLabel.innerHTML);
            let minutes = parseInt(realMinutesLabel.innerHTML);
            let seconds = parseInt(realSecondsLabel.innerHTML);

            // --- Increment Logic ---
            seconds++;

            if (seconds === 60) {
                seconds = 0;
                minutes++;

                if (minutes === 60) {
                    minutes = 0;
                    hours++;
                }
            }

            // Update the DOM elements with the new, padded time
            realHoursLabel.innerHTML = pad(hours);
            realMinutesLabel.innerHTML = pad(minutes);
            realSecondsLabel.innerHTML = pad(seconds);

            // Update hidden fields for form submission
            document.getElementById("real_duration_hours").value = hours;
            document.getElementById("real_duration_minutes").value = minutes;
            document.getElementById("real_duration_seconds").value = seconds;
        }

        /**
         * Initializes and starts the real duration timer (counting up).
         */
        function start_real_duration_timer() {
            // Get the labels by fetching them from the DOM
            const realHoursLabel = document.getElementById("real_hours");
            const realMinutesLabel = document.getElementById("real_minutes");
            const realSecondsLabel = document.getElementById("real_seconds");

            // Clear any existing interval before starting a new one
            if (real_duration_int) {
                clearInterval(real_duration_int);
            }

            // Set up the interval to call setRealDuration every 1000 milliseconds (1 second)
            real_duration_int = setInterval(function() {
                setRealDuration(realHoursLabel, realMinutesLabel, realSecondsLabel)
            }, 1000);
        }

        /**
         * Stops the running real duration timer.
         */
        function stop_real_duration_timer() {
            clearInterval(real_duration_int);
        }

        /**
         * Resets the real duration timer to 00:00:00.
         */
        function reset_real_duration_timer() {
            stop_real_duration_timer();
            document.getElementById("real_hours").innerHTML = "00";
            document.getElementById("real_minutes").innerHTML = "00";
            document.getElementById("real_seconds").innerHTML = "00";
            document.getElementById("real_duration_hours").value = 0;
            document.getElementById("real_duration_minutes").value = 0;
            document.getElementById("real_duration_seconds").value = 0;
        }

    </script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Edit Existing Production Plan</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <button type="button" name="btn_start_production"  style="display:{{ $plan_info->pp_start_production == 1 ? 'none' : '' }}"  id="BTN_START_PRODUCTION" class="btn btn-accent m-btn m-btn--icon" onclick="start_real_duration_timer();">
					<span>
						<i class="fa fa-play"></i>
						<span>
							Start Production
						</span>
					</span>
                    </button>
                    <button type="button" name="btn_quality_check"  id="BTN_QUALITY_CHECK"  style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-outline-brand m-btn m-btn--icon">
					<span>
						<i class="fa fa-check-square"></i>
						<span>
							Quality Check
						</span>
					</span>
                    </button>
                    <button type="button" name="btn_pause_production" id="BTN_PAUSE_PRODUCTION" style="display:{{ $plan_info->pp_start_production == 1 ? '' : 'none' }}" class="btn btn-warning m-btn m-btn--icon" onclick="stop_real_duration_timer();">
					<span>
						<i class="fa  fa-pause"></i>
						<span>
							Pause
						</span>
					</span>
                    </button>
                    <button type="button" name="btn_block_production"  id="BTN_BLOCK_PRODUCTION" style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-danger m-btn m-btn--icon" onclick="stop_real_duration_timer();">
                        Block
                    </button>
                    <button type="button" name="btn_scrap" id="BTN_SCRAP" style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-outline-success m-btn m-btn--icon m-btn--outline-2x">
					<span>
						<i class="la la-file-excel-o"></i>
						<span>
							Scrap
						</span>
					</span>
                    </button>
                    <button type="button" name="btn_quality_alert"  id="BTN_QUALITY_ALERT"  style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}" class="btn btn-outline-brand m-btn m-btn--icon">
					<span>
						<i class="fa  fa-warning"></i>
						<span>
							Quality Alert
						</span>
					</span>
                    </button>
                    <button  id="BTN_MAINT_REQUEST" name="btn_maint_request"  style="display:{{ $plan_info->pp_run_production == 1 ? '' : 'none' }}"  type="button" class="btn btn-outline-accent m-btn m-btn--outline-2x ">
					<span>
						<i class="flaticon-file-1"></i>
						<span>
							Maintenance Request
						</span>
					</span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="height:50px;">&nbsp;</div>
            </div>
            <form name="frm_save_plan" id="FORM_SAVE_PLAN">
                <div class="form-body">
                     <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="pp_id" id="PP_ID" value="{{ $plan_info->pp_id }}" />
                        <input type="hidden" name="timer_minutes" id="timer_minutes" value="{{ $timer_array[1] }}" />
                        <input type="hidden" name="timer_seconds" id="timer_seconds" value="{{ $timer_array[2] }}" />
                        <input type="hidden" name="timer_hours" id="timer_hours" value="{{ $timer_array[0] }}" />
                        <input type="hidden" name="real_duration_hours" id="real_duration_hours" value="{{ $plan_info->pp_real_duration_hours ?? 0 }}" />
                        <input type="hidden" name="real_duration_minutes" id="real_duration_minutes" value="{{ $plan_info->pp_real_duration_minutes ?? 0 }}" />
                        <input type="hidden" name="real_duration_seconds" id="real_duration_seconds" value="{{ $plan_info->pp_real_duration_seconds ?? 0 }}" />
                        <input type="hidden" name="production_run" id="production_run" value="0" />
                    </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Production Plan Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Plan Code <span class="required"> * </span></label>
                                <input type="text" name="pp_plan_code" id="PP_PLAN_CODE" class="form-control" required="required" maxlength="15"  value="{{ $plan_info->pp_plan_code }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Plan Label <span class="required"> * </span></label>
                                <input type="text" name="pp_plan_label" id="PP_PLAN_LABEL" class="form-control" required="required" maxlength="255"  value="{{ $plan_info->pp_plan_label }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Manager </label>
                                <select class="bs-select form-control" name="pp_production_manager" id="PP_PRODUCTION_MANAGER" data-actions-box="true">
                                    <option value="">-- Plan Manager --</option>
                                    @foreach ( $lst_users as $key => $user_info )
                                        <option {{ $plan_info->pp_production_manager == $user_info->id ? "selected" : "" }} value="{{ $user_info->id }}">{{ $user_info->u_fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Status</label>
                                <select class="bs-select form-control" name="pp_plan_status" id="PP_PLAN_STATUS" data-actions-box="true">
                                    <option value="">-- Plan Status --</option>
                                    @foreach ( $lst_plan_status as $key => $status_info )
                                        <option {{ $plan_info->pp_plan_status == $status_info->ps_id ? "selected" : "" }} value="{{ $status_info->ps_id }}">{{ $status_info->ps_status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Bill of Materials</label>
                                <select class="bs-select form-control" name="pp_bom_id" id="PP_BOM_ID" data-actions-box="true">
                                    <option value="">-- Bill of Materials --</option>
                                    @foreach ( $lst_bom_info as $key => $bom_info )
                                        <option value="{{ $bom_info->bm_id }}">{{ $bom_info->bm_code }} {{ $bom_info->bm_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Target Warehouse</label>
                                <select class="bs-select form-control" name="pp_target_warehouse" id="PP_TARGET_WAREHOUSE" data-actions-box="true">
                                    <option value="0">-- Target warehouse --</option>
                                    @foreach ( $lst_warehouses as $key => $warehouse_info )
                                        <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Bill of Materials</label>
                                <select class="bs-select form-control" name="pp_bom_id" id="PP_BOM_ID" data-actions-box="true">
                                    <option value="0">-- Bill of Materials --</option>
                                    @foreach ( $lst_bom_info as $key => $bom_info )
                                        <option {{ $plan_info->pp_bom_id == $bom_info->bm_id ? "selected" : "" }} value="{{ $bom_info->bm_id }}">{{ $bom_info->bm_code }} {{ $bom_info->bm_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Plan Customer</label>
                                <select class="bs-select form-control" name="pp_customer_id" id="PP_CUSTOMER_ID" data-actions-box="true">
                                    <option value="">-- Customer --</option>
                                    @foreach ( $lst_customers as $key => $customer_info )
                                        <option {{ $plan_info->pp_customer_id == $customer_info->ic_id ? "selected" : "" }} value="{{ $customer_info->ic_id }}">{{ $customer_info->ic_customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" {{  $plan_info->pp_is_finished == 1 ? "checked" : "" }} type="checkbox" name="pp_is_finished" id="PP_IS_FINISHED" value="1" />
                                    <span class="form-check-label fw-semibold text-muted">
                  Finish Production
                </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <br />
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" {{  $plan_info->pp_is_approved == 1 ? "checked" : "" }} type="checkbox" name="pp_is_approved" id="PP_IS_APPROVED" value="1" />
                                    <span class="form-check-label fw-semibold text-muted">
                  Plan Approved
                </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> Currency</label>
                                <select class="bs-select form-control" name="pp_currency_id" id="PP_CURRENCY_ID" data-actions-box="true">
                                    <option value="">-- Currency --</option>
                                    @foreach ( $lst_currencies as $key => $currency_info )
                                        <option {{  $plan_info->pp_currency_id == $currency_info->cc_id ? "selected" : "" }} value="{{ $currency_info->cc_id }}">{{ $currency_info->cc_currency_code }} - {{ $currency_info->cc_currency_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Total Stock Price</label>
                                <input type="text" name="pp_total_stock_price" id="PP_TOTAL_STOCK_PRICE" class="form-control"  maxlength="255"  value="{{ $plan_info->pp_total_stock_price }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Prepare Date</label>
                                <input type="text" name="pp_prepare_date" id="PP_PREPARE_DATE" class="form-control" readonly="readonly" maxlength="255"  value="{{ $plan_info->pp_prepare_date  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> End Date</label>
                                <input type="text" name="pp_end_date" id="PP_END_DATE" class="form-control" maxlength="255"  readonly="readonly"  value="{{ $plan_info->pp_end_date  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Start Date</label>
                                <input type="text" name="pp_start_date" id="PP_START_DATE" class="form-control" maxlength="255"  readonly="readonly"  value="{{ $plan_info->pp_start_date  }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Finish Date</label>
                                <input type="text" name="pp_finish_date" id="PP_FINISH_DATE" class="form-control" maxlength="255"  readonly="readonly" value="{{ $plan_info->pp_finish_date }}" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label"> Estimation Time</label><br/>
                                <div class='input-group timepicker' id='PT_ESTIMATION_TIME' >
                                    <div class="input-group-prepend">
										<span class="input-group-text">
											<i class="la la-clock-o"></i>
										</span>
                                    </div>
                                    <input type='text' id="PP_ESTIMATION_TIME" name="pp_estimation_time" class="form-control m-input" placeholder="Select time" value="{{ $plan_info->pp_estimation_time }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Work Instruction</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_3">Time Tracking</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_4">Quality Check</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"> Work Instruction <span class="required"> * </span></label><br/>
                                                <textarea style="width:100%;height:250px;resize:none" id="PP_PLAN_DESCRIPTION"  class="form-control" name="pp_plan_description"  cols="">{{ $plan_info->pp_plan_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12" id="LstPlanProducts">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" align="right">
                                            <button type="button" name="btn_add_product" id="BTN_ADD_PRODUCT" class="btn btn-success" >Add Product</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> Prepare Date <span class="required"> * </span></label>
                                                <input type="text" name="pp_prepare_date" id="PP_PREPARE_DATE" class="form-control"  readonly="readonly" maxlength="255"  value="{{ $plan_info->pp_prepare_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> End Date</label>
                                                <input type="text" name="pp_end_date" id="PP_END_DATE" class="form-control"  maxlength="255"  readonly="readonly"  value="{{ $plan_info->pp_end_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> Start Date</label>
                                                <input type="text" name="pp_start_date" id="PP_START_DATE" class="form-control"  maxlength="255"  readonly="readonly"  value="{{ $plan_info->pp_start_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> Finish Date</label>
                                                <input type="text" name="pp_finish_date" id="PP_FINISH_DATE" class="form-control"  maxlength="255"  readonly="readonly" value="{{ $plan_info->pp_finish_date }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> Estimation Time</label><br/>
                                                <span class="m-badge m-badge--accent m-badge--wide" style="font-size:16px;">{{ $plan_info->pp_estimation_time }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> Remaining Time </label><br/>
                                                <span class="m-badge m-badge--focus m-badge--wide">
                                                    <label id="hours">{{ $timer_array[0] }}</label><span class='bigger'>:</span><label id="minutes">{{ $timer_array[1] }}</label><span class='bigger'>:</span><label id="seconds">{{ $timer_array[2] }}</label>
                                                 </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label"> Real Duration </label><br/>
                                                <span class="m-badge m-badge--success m-badge--wide RealDuration">
                                                    <label id="real_hours">{{ $plan_info->pp_real_duration_hours ?? '00' }}</label><span class='bigger'>:</span><label id="real_minutes">{{ $plan_info->pp_real_duration_minutes ?? '00' }}</label><span class='bigger'>:</span><label id="real_seconds">{{ $plan_info->pp_real_duration_seconds ?? '00' }}</label>
                                                 </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12" id="LstQualityCheck">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" name="btn_save_plan" id="BTN_SAVE_PLAN"  class="btn btn-info">Save</button>
                            <button type="button" id="BACK_FORM" name="back_form" class="btn default">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="PlanItemsModel" tabindex="-1" role="dialog" aria-labelledby="PlanItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PlanItemsModalLabel">Plan Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="frm_plan_item" id="FRM_PLAN_ITEMS">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                    </span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Product </label><br/>
                                    <select name="pi_item_product" id="PI_ITEM_PRODUCT" style="width:100%;" class="form-control">
                                        <option value="">-- Select product --</option>
                                        @foreach($lst_products as $index => $product_info)
                                            <option value="{{ $product_info->p_id }}" >{{ $product_info->p_product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Quanity </label><br/>
                                    <input type="number" class="form-control" name="pi_item_quanity" id="PI_ITEM_QUANTITY" required="required" min="0.0000" max="999999999999.0000" />
                                </div>
                            </div>
                            <div class="col-md-12" align="right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="btn_insert_item" id="BTN_INSERT_ITEM" class="btn btn-primary">Save Item</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="PlanQualityCheckModel" tabindex="-1" role="dialog" aria-labelledby="QualityCheckModelLabel"  aria-hidden="true">
        <div class="modal-dialog" role="document"><!-- PlanQualityCheckModel -->
            <div class="modal-content" style="width:800px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="QualityCheckModelLabel"> Quality Check </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="frm_quality_check" id="FRM_QUALITY_CHECK">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                        <input type="hidden" name="qc_id" id="QC_ID" value="" />
                    </span>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Check Label </label><br/>
                                    <input type="text" name="qc_check_label" class="form-control" value="" maxlength="255" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Team </label><br/>
                                    <select name="qc_team_id" id="QC_TEAM_ID" style="width:100%;" class="form-control">
                                        <option value="">-- Select Team --</option>
                                        @foreach($lst_teams as $index => $team_info)
                                            <option value="{{ $team_info->ut_id }}" >{{ $team_info->ut_team }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Product </label><br/>
                                    <select name="qc_product_id" id="QC_PRODUCT_ID" style="width:100%;" class="form-control">
                                        <option value="">-- Select product --</option>
                                        @foreach($lst_products as $index => $product_info)
                                            <option value="{{ $product_info->p_id }}" >{{ $product_info->p_product_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Status </label><br/>
                                    <select name="qc_status_id" id="QC_STATUS_ID" style="width:100%;" class="form-control">
                                        <option value="">-- Select Status --</option>
                                        @foreach($lst_check_status as $index => $cs_info)
                                            <option value="{{ $cs_info->cs_id }}" >{{ $cs_info->cs_status_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Comment </label><br/>
                                    <textarea style="width:100%;height:250px;"  name="qc_description" id="QC_DESCRIPTION"  class="form-control" ></textarea>
                                </div>
                            </div>
                            <div class="col-md-12" align="right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" name="btn_check_pass" id="BTN_CHECK_PASS" class="btn btn-primary">Pass</button>
                                <button type="button" name="btn_check_fail" id="BTN_CHECK_FAIL" class="btn btn-danger">Fail</button>
                                <button type="submit" name="btn_save_check" id="BTN_SAVE_CHECK" class="btn btn-success">Save Check</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="AssignPlanModel" tabindex="-1" role="dialog" aria-labelledby="AssignPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AssignPlanModalLabel">Plan Assign To</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="frm_plan_assign_to" id="FRM_PLAN_ASSIGN_TO">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                    </span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> Assign To </label><br/>
                                    <select name="pp_assign_to" id="PP_ASSIGN_TO" style="width:100%;" class="form-control">
                                        <option value="">-- Select User --</option>
                                        @foreach($lst_prod_dep_users as $index => $user_info)
                                            <option value="{{ $user_info->id }}" >{{ $user_info->u_fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" align="right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="btn_assign_user" id="BTN_ASSIGN_USER" class="btn btn-primary">Save Item</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    @if($plan_info->pp_approval_user == 0)
        <div class="modal fade" id="ProductionPlanApprovalModel" tabindex="-1" role="dialog" aria-labelledby="labelProductionPlanApprovalModel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="labelProductionPlanApprovalModel">Plan Approval</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form name="frm_plan_approval" id="FRM_PLAN_APPROVAL">
      		       <span id="hidden_fields">
                        {!! csrf_field() !!}
                        <input type="hidden" name="fk_plan_id" id="FK_PLAN_ID" value="{{ $plan_info->pp_id }}" />
                    </span>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> Approval Message </label><br/>
                                        <textarea style="width:100%;height:250px;" name="pp_approve_note" class="form-control" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12" align="right">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="btn_approval_user" id="BTN_APPROVAL_USER" class="btn btn-primary">Plan Approve</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
