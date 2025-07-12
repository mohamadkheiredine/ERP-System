<?php
/***********************************************************
 * addemployee.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/9/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>


@extends('layouts.layout',['page_title' => "PayRoll Management"])

@section('themes')
    <style>
        th{
            cursor: pointer;
        }
        #ModelPopUp{
            width:800px;
        }
        .card-body{
            min-height:600px;
        }
    </style>
@endsection
@section('plugins')
    <script src="https://cdn.ckeditor.com/ckeditor5/12.2.0/classic/ckeditor.js"></script>
    <script type="text/javascript" src="{{ url('js/modules/employees.module.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/libraries/payrolls/saveemployees.js') }}"></script>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Add New Employee</h3>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">
            <form name="form_save_employees" autocomplete="off" id="FORM_SAVE_EMPLOYEES">
                <div  class="form-body">
             <span id="hidden_fields">
                {!! csrf_field() !!}
            </span>
                    <div class="alert alert-success" style="display:none">
                        <strong>Success!</strong> Employee Information is saved successfully!
                    </div>
                    <div class="alert alert-danger" style="display:none">
                        <strong>Error!</strong> You have some form errors. Please check below.
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img id="PROFILE_PIC" height="120" src="{{ url('images/NoImageAvailable.jpg') }}" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>

                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="clearfix margin-top-10">
                                        <div>
                                        <span class="btn default btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <input type="file" name="u_profile_pic" id="U_PROFILE_PIC" /> </span>
                                        </div>
                                        <br>
                                        <span class="label label-danger"> NOTE! </span><br><br>
                                        <span> Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">

                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Employee Information</h3>
                                    <div class="card-toolbar">

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Full Name <span class="required"> * </span></label>
                                                <input type="text" maxlength="500" autocomplete="off" name="u_fullname" id="U_FULLNAME" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Access Code<span class="required"> * </span></label>
                                                <input type="text" autocomplete="off" maxlength="5" readonly="readonly" name="u_attendance_code" id="U_ATTENDANCE_CODE" class="form-control" value="{{ $rand }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label> User Type <span class="required"> * </span></label>
                                                <select  name="u_user_type" id="U_USER_TYPE" class="form-select" data-control="select2" data-placeholder="Select user type">
                                                    <option value="">No User Type</option>
                                                    @foreach($lst_user_types as $key => $ut_info)
                                                        <option value="{{ $ut_info->ut_id }}">{{ $ut_info->ut_user_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">Address</label>
                                                <textarea style="width:100%;height: 100px;resize:none" class="form-control" name="u_address" id="U_ADDRESS"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="height: 40px;">
                                            <div class="col-12">
                                                <br/>
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" name="u_is_active" value="1"  />
                                                    <span class="form-check-label fw-semibold text-muted">
                                                        Enable User
                                                  </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row" style="height:5px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h3 class="card-title">Personal Information</h3>
                                    <div class="card-toolbar">

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Gender</label><br/>
                                                <div class="mt-radio-list" data-error-container="#GenderError">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="radio" checked="checked" name="u_gender"  value="m" /> Male
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="radio"  name="u_gender"  value="f" /> Female
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="GenderError"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Residential Area</label>
                                                <textarea style="width:100%;height: 100px;resize:none" class="form-control" name="u_residential_area" id="U_RESIDENTIAL_AREA"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Email <span class="required"> * </span></label>
                                                <input type="email" maxlength="255" name="u_email" id="U_EMAIL" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Date Of Birth <span class="required"> * </span></label>
                                                <input type="text" name='u_date_birth' class="form-control" id="U_DATE_BIRTH" />
                                            </div>
                                        </div><div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">mobile</label>
                                                <input type="text" maxlength="20" name="u_mobile" id="U_MOBILE" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Phone</label>
                                                <input type="text" maxlength="20" name="u_phone" id="U_PHONE" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Fax</label>
                                                <input type="text" maxlength="20" name="u_fax" id="U_FAX" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Website</label>
                                                <input type="text" name="u_website" id="U_WEBSITE" class="form-control" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Marital Status</label>
                                                <select  name="u_marital_status" id="U_MARITAL_STATUS" class="form-select" data-control="select2" data-placeholder="Select Marital Status">
                                                    <option value="">-- Select One --</option>
                                                    <option value="1">single</option>
                                                    <option value="2">married</option>
                                                    <option value="3">widowed</option>
                                                    <option value="4">divorced</option>
                                                    <option value="5">separated</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Number of Dependency</label>
                                                <input type="number" name='u_number_of_dependencies' step="1" min="0" class="form-control" id="U_NUMBER_OD_DEPENDENCIES" value="0" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success">
                                    <h3 class="card-title">Employment Information</h3>
                                    <div class="card-toolbar">

                                    </div>
                                </div>
                                <div class="card-body card-scroll h-200px">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Company</label>
                                                <select  name="fk_company_id" id="FK_COMPANY_ID" class="form-select" data-control="select2" data-placeholder="Select Company">
                                                    <option value="">--Select One--</option>
                                                    @foreach( $lst_companies as $key => $cmp_info)
                                                        <option value="{{ $cmp_info->cd_id }}">{{ $cmp_info->cd_company_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Department</label>
                                                <select  name="u_department_id" id="U_DEPARTMENT_ID" class="form-select" data-control="select2" data-placeholder="Select department">
                                                    <option value="">--Select One--</option>
                                                    @foreach( $lst_departments as $key => $dep_info)
                                                        <option value="{{ $dep_info->sd_id }}">{{ $dep_info->sd_department_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Warehouse Responsible</label>
                                                <select  name="fk_warehouse_id" id="FK_WAREHOUSE_ID" class="form-select" data-control="select2" data-placeholder="Select warehouse">
                                                    <option value="">--Select One--</option>
                                                    @foreach( $lst_warhouses as $key => $warehouse_info)
                                                        <option value="{{ $warehouse_info->w_id }}">{{ $warehouse_info->w_warehouse_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Job Role</label>
                                                <select name="u_job_role_id" id="U_JOB_ROLE_ID" class="form-select" data-control="select2" data-placeholder="Select Job Role">
                                                    <option value="">--Select One--</option>
                                                    @foreach( $lst_job_roles as $key => $jr_info)
                                                        <option value="{{ $jr_info->jr_id }}">{{ $jr_info->jr_job_role }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Job Title</label>
                                                <select name="u_job_title_id" id="U_JOB_TITLE_ID" class="form-select" data-control="select2" data-placeholder="Select Job Title">
                                                    <option value="">--Select One--</option>
                                                    @foreach( $lst_job_titles as $key => $jt_info)
                                                        <option value="{{ $jt_info->jt_id }}">{{ $jt_info->jt_job_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Employee Type</label>
                                                <select name="u_employee_type" id="U_EMPLOYEE_TYPE" class="form-select" data-control="select2" data-placeholder="Select Employee Type">
                                                    <option value="">--Select One--</option>
                                                    @foreach( $lst_employment_type as $key => $et_info)
                                                        <option value="{{ $et_info->et_id }}">{{ $et_info->et_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Employment Date <span class="required"> * </span></label>
                                                <input type="text" name='u_employment_date' readonly="readonly" class="form-control" id="U_EMPLOYMENT_DATE" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Daily Working Hour </label>
                                                <input type="number" name='u_daily_working_hours' step="0.1" class="form-control" id="U_DAILY_WORKING_HOURS" value="9.5" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Sales Commission</label>
                                                <input type="text" name='u_sales_commission' maxlength="3" class="form-control" id="U_SALES_COMISSION" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">User Sallary</label>
                                                <input type="text" name='u_user_sallary' maxlength="50" class="form-control" id="U_USER_SALLARY" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Hourly Rate</label>
                                                <input type="text" name='u_hourly_rate' maxlength="50" class="form-control" id="U_HOURLY_RATE" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Holidays</label>
                                                <input type="number" min="0" max="100" step="1"  name='u_number_holidays' class="form-control" id="U_NUMBER_HOLIDAYS" value="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="height: 40px;">
                                            <div class="form-group">


                                                <br/>
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" name="u_has_insurance" value="1"  />
                                                    <span class="form-check-label fw-semibold text-muted">
                                                   Has Insurance
                                                  </span>
                                                </label>


                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">CNSS Number</label>
                                                <input type="text" name='u_cnss_number' maxlength="20" class="form-control" id="U_USER_SALLARY" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Account Number</label>
                                                <input type="text" name='pm_account_number' maxlength="255" class="form-control" id="PM_ACCOUNT_NUMBER" value="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Payroll Payment Type</label>
                                                <select name="pm_payment_method" id="PM_PAYMENT_TYPE" class="form-select" data-control="select2" data-placeholder="Select Payment Type">
                                                    <option value="">--Select One--</option>
                                                    <option value="bank-transkfer">Bank Transfer</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="Check">Check</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="height:10px;"></div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3" align="right">
                            <button type="submit" id="BTN_SAVE_EMPLOYEE" name="btn_save_employee" class="btn btn-primary btn-wide">Save</button>&nbsp;&nbsp;<button type="button" id="BACK_FORM" name="back_form" class="btn btn-dark btn-wide">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

