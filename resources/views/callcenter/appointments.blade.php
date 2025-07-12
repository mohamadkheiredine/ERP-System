<?php
/***********************************************************
appointments.blade.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : Oct 1, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>

@extends('layouts.layout',['page_title' => "Call center Management"])

@section('themes')
<style>
    textarea{
        resize:none;
    }
    div#kt_docs_card_collapsible {
        padding:10px;
    }
            .form-control{
        color:#0000FF !important;
    }
</style>
@endsection
@section('plugins')
<script type="text/javascript" src="{{ url('js/modules/callapt.module.js') }}"></script>
<script type="text/javascript" src="{{ url('js/libraries/callcenter/appointments.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const table = document.querySelector('.table-apt');
        const headers = table.querySelectorAll('th[data-sort]');
        const tbody = table.querySelector('tbody');

        let sortColumn = null;
        let sortDirection = 1; // 1 for ascending, -1 for descending

        headers.forEach((header, i) => {
            header.style.cursor = "pointer";
            header.addEventListener("click", function () {
                const type = header.getAttribute('data-sort');
                sortDirection = (sortColumn === i) ? -sortDirection : 1;
                sortColumn = i;
                sortTableByColumn(tbody, i, sortDirection);
                // Optional: Show sort arrow
                headers.forEach(h => h.innerHTML = h.innerText); // Reset
            });
        });

        function sortTableByColumn(tbody, column, direction) {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort((a, b) => {
                let cellA = a.children[column].innerText.trim();
                let cellB = b.children[column].innerText.trim();

                // Try to compare as numbers if possible
                if (!isNaN(cellA) && !isNaN(cellB)) {
                    cellA = Number(cellA);
                    cellB = Number(cellB);
                }
                // Try to compare as dates if the column is "Last Call Date" or similar
                else if (column === 11 || column === 14) { // update these indexes for date columns
                    cellA = new Date(cellA);
                    cellB = new Date(cellB);
                }
                return (cellA > cellB ? 1 : cellA < cellB ? -1 : 0) * direction;
            });
            rows.forEach(row => tbody.appendChild(row));
        }
    });
</script>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">Appointments Management</h3>
        <div class="card-toolbar">
            <div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
              </button>
              <ul class="dropdown-menu">
                  <li><a class="dropdown-item" data-action_type="DOWNLOAD_APPOINTMENT" href="#">Download Appointment</a></li>
              </ul>
            </div>
        </div>
    </div>
    <div class="card-body">
             <div class="form-body">
                 <div class="alert alert-success" style="display:none">
                    <strong>Success!</strong> Appointment Information is saved successfully!
                 </div>
                 <div class="alert alert-danger" style="display:none">
                   <strong>Error!</strong> You have some form errors. Please check below.
                 </div>
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card shadow-sm">
                                  <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_collapsible">
                                        <h3 class="card-title">Create New Appointment</h3>
                                        <div class="card-toolbar rotate-180">
                                            <i class="ki-duotone ki-down fs-1"></i>
                                        </div>
                                    </div>
                                    <div id="kt_docs_card_collapsible" class="collapse show">

                                        <form name="frm_create_apt" id="FRM_CREATE_APT">

                  <span id="hidden_fields">
                   {!! csrf_field() !!}
                   <input type="hidden" name="ca_id" value="0" />
                 </span>
                                    <div class="row">
                                         <div class="col-md-4 LeadDropdown">
                                                <div class="form-group">
                                                  <label>Leads <span class="required"> * </span> </label>
                                                  <select name="lead_id" required="required" id="LEAD_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Lead">
                                                          <option value="">-- Select Lead --</option>
                                                          <?php foreach ( $lst_leads as $key => $lead_info ) { ?>
                                                                  <option value="{{ $lead_info->cl_id }}">{{ $lead_info->cl_first_name }}&nbsp;{{ $lead_info->cl_last_name }}</option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Date </label>
                                                <input type="text" class="form-control" readonly="readonly" name="ca_apt_date" id="CA_APT_DATE" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Time </label>
                                                <input type="text" class="form-control" name="ca_apt_time" id="CA_APT_TIME" value="" maxlength="15" />
                                            </div>
                                        </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Salesman <span class="required"> * </span> </label>
                                                  <select name="cl_sales_id" required="required" id="CL_SALES_ID"  class="form-control form-select" data-control="select2" data-placeholder="Salesman">
                                                          <option value="">-- Select User --</option>
                                                          <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                                                  <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Telemarketing <span class="required"> * </span>  </label>
                                                      <select name="cl_telemarketing_id" id="CL_TELEMARKETING_ID" class="form-control form-select" data-control="select2" data-placeholder="Select Telemarketing">
                                                          <option value="">-- Select Telemarketing --</option>
                                                          <?php foreach ( $lst_telemarketing as $key => $user_info ) { ?>
                                                                  <option {{ Session('user_id') == $user_info->id ? "selected" : "" }} value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Lead Types</label>
                                                      <select name="cl_lead_type" id="CL_LEAD_TYPE" class="form-control form-select" data-control="select2" data-placeholder="Select Lead Type">
                                                          <option value="">-- Select Lead Type --</option>
                                                          <?php foreach ( $lst_lead_types as $key => $type_info ) { ?>
                                                                  <option  value="<?php echo $type_info->lt_id;  ?>"><?php echo $type_info->lt_deal_type;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                          <div class="col-md-4">
                                                <div class="form-group">
                                                  <label>Result</label>
                                                      <select name="ca_apt_result" id="CA_APT_RESULT" class="form-control form-select" data-control="select2" data-placeholder="Pending">
                                                          <option value="">Pending</option>
                                                          <?php foreach ( $lst_appt_results as $key => $res_info ) { ?>
                                                                  <option  value="<?php echo $res_info->ar_id;  ?>"><?php echo $res_info->ar_app_result;  ?></option>
                                                          <?php  } ?>
                                                  </select>
                                              </div>
                                          </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Full Name </label>
                                                <input type="text" class="form-control" name="cl_full_name" id="CL_FULL_NAME"  value="" maxlength="255" />
                                            </div>
                                        </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Phone </label>
                                                <input type="text" class="form-control" name="cl_phone"  id="CL_PHONE" value="" maxlength="45" />
                                            </div>
                                        </div>
                                          <div class="col-md-4">
                                            <div class="form-group">
                                                <label>With </label>
                                                <input type="text" class="form-control" name="ca_apt_with" id="CA_APT_WITH" value="" maxlength="255" />
                                            </div>
                                        </div>
                                          <div class="col-md-4 AptJob">
                                            <div class="form-group">
                                                <label>Job </label>
                                                <input type="text" class="form-control" name="ca_apt_job" id="CA_APT_JOB" value="" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 FieldJob">
                                            <div class="form-group">
                                                <label>Area </label>
                                                <input type="text" class="form-control" name="cl_area" id="CL_AREA" value="" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Address </label>
                                                <input type="text" class="form-control" name="ca_lead_address" id="CA_LEAD_ADDRESS"  value="" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Referred By </label>
                                                <input type="text" class="form-control" name="cl_referred_by" id="CL_REFERRED_BY" value="" maxlength="255" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Number of Leads </label>
                                                <input type="text" class="form-control" name="ca_nbr_leads" id="CA_NBR_LEADS" value="0" maxlength="5" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <br/>
                                               <label class="form-check form-switch form-check-custom form-check-solid">
                                                     <input class="form-check-input" type="checkbox" name="ca_lead_confirm"  id="CA_LEAD_CONFIRM"  value="1"  />
                                                     <span class="form-check-label fw-semibold text-muted">
                                                        Confirmed
                                                     </span>
                                                 </label>
                                            </div>
                                       </div>
                                        <div class="col-md-8"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Notes </label>
                                                <textarea name="ca_apt_notes" id="CA_APT_NOTES" class="form-control" style="width:100%;height:250px"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Details </label>
                                                <textarea name="ca_apt_details" id="CA_APT_DETAILS" class="form-control" style="width:100%;height:250px"></textarea>
                                            </div>
                                        </div>
                                        <div style="height:50px" class="col-md-12"></div>
                                        <div style="text-align: right" class="col-md-12">
                                            <button name="btn_save_app" class="btn btn-primary" type="submit">Save Info</button>
                                            <button name="btn_reset" class="btn btn-danger" type="reset">Reset</button>
                                        </div>
                                    </div>
                                        </form>
                                </div>
                            </div>
                            <div class="row">
                                <div style="height:50px" class="col-md-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card shadow-sm">
                                      <div class="card-header collapsible cursor-pointer rotate" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_search_apt">
                                          <h3 class="card-title">Search Appointment</h3>
                                          <div class="card-toolbar rotate-180">
                                              <i class="ki-duotone ki-down fs-1"></i>
                                          </div>
                                      </div>
                                        <div id="kt_docs_card_search_apt" class="collapse show" style="padding:25px;">
                                            <form name="frm_search_apt">

                  <span id="hidden_fields">
                   {!! csrf_field() !!}
                 </span>
                                            <div class="row">
                                                <div class="col-md-4">
                                                     <div class="form-group">
                                                        <label class="control-label"> Date </label><br/>
                                                        <input type="text" name="ld_apt_date" id="LD_APT_DATE" class="form-control" value="{{ date('Y-m-d') }}" />
                                                     </div>
                                                </div>
                                                <div class="col-md-8">&nbsp;</div>
                                                <div class="col-md-6">
                                                     <div class="form-group">
                                                        <label class="control-label"> From Date </label><br/>
                                                        <input type="text" name="ld_from_apt_date" id="LD_FROM_APT_DATE" class="form-control" value="" />
                                                     </div>
                                                </div>
                                                 <div class="col-md-6">
                                                     <div class="form-group">
                                                        <label class="control-label"> To Date </label><br/>
                                                        <input type="text" name="ld_to_apt_date" id="LD_TO_APT_DATE" class="form-control" value="" />
                                                     </div>
                                                </div>
                                                 <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label>Salesman  </label>
                                                          <select name="ca_salesman_id"  id="CA_SALESMAN_ID"  class="form-control form-select" data-control="select2" data-placeholder="Select Salesman">
                                                                  <option value="0">-- Select Salesman --</option>
                                                                  <?php foreach ( $lst_sales as $key => $user_info ) { ?>
                                                                          <option value="<?php echo $user_info->id;  ?>"><?php echo $user_info->u_fullname;  ?></option>
                                                                  <?php  } ?>
                                                          </select>
                                                      </div>
                                                  </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Result</label>
                                                        <select name="ap_apt_result" id="AP_APT_RESULT" class="form-control form-select" data-control="select2" data-placeholder="Select Apt Result">
                                                            <option value="">Pending</option>
                                                            <?php foreach ( $lst_appt_results as $key => $res_info ) { ?>
                                                            <option  value="<?php echo $res_info->ar_id;  ?>"><?php echo $res_info->ar_app_result;  ?></option>
                                                            <?php  } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">&nbsp;</div>
                                                <div class="col-md-12" style="text-align: right">
                                                    <button type="button" name="btn_search" class="btn btn-primary">search</button>
                                                    <button type="reset" name="btn_reset" class="btn btn-danger">Reset</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div style="height:50px" class="col-md-12"></div>
                            </div>
                         <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                    <table class="table table-bordered table-apt">
                                                                <thead class="table-header">
                                                                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                                                                <th title="#"></th>
                                                                                <th title="Date" data-sort="date"> Date </th>
                                                                                <th title="Time" data-sort="time"> Time </th>
                                                                                <th title="Full Name"  data-sort="string"> Lead Name </th>
                                                                                <th title="Area"  data-sort="string"> Area </th>
                                                                                <th title="Salesman"  data-sort="string"> Salesman </th>
                                                                                <th title="Result"  data-sort="string"> Result </th>
                                                                                <th title="Telemarketing"  data-sort="string"> Telemarketing </th>
                                                                            <th title="MobileNumber"  data-sort="string"> Mobile Number </th>
                                                                                <th title="Confirmed"  data-sort="string"> Confirmed </th>
                                                                                <th title="edit">  </th>
                                                                                <th title="delete">  </th>
                                                                        </tr>
                                                                </thead>
                                                                <tbody id="LstLeadAppts" class="table-tbody">

                                                                </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                     </div>
                 </div>
             </div>
    </div>
</div>
@endsection
