<?php
/***********************************************************
dashboard.blade.php
Product :
Version : 1.0
Release : 2
Date Created :Sep 5, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

?>


@extends('layouts.layout',['page_title' => "Dashboard"])

@section('plugins')
    <script src="{{ url('admin/assets/pages/scripts/dashboard.js') }}" type="text/javascript"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" style="height: 800px;">
        <ul class="IconsMenu">
            <li>
                <a href="#">
                    <img src="{{ url('icons/discuss.png') }}" /><br/>
                    <label>Discuss</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/calendar.png') }}" /><br/>
                    <label>Calendar</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/notes.png') }}" /><br/>
                    <label>Notes</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/contacts.png') }}" /><br/>
                    <label>Contacts</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/sales.png') }}" /><br/>
                    <label>Sales</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/warehouse.png') }}" /><br/>
                    <label>Warehouse</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/lead-automation.png') }}" /><br/>
                    <label>Automation</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/documents.png') }}" /><br/>
                    <label>Documents</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/members.png') }}" /><br/>
                    <label>Members</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/point-of-sales.png') }}" /><br/>
                    <label>POS</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/bills.png') }}" /><br/>
                    <label>Bills</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/purchases.png') }}" /><br/>
                    <label>Purchases</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/inventory.png') }}" /><br/>
                    <label>Inventory</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/manufacturing.png') }}" /><br/>
                    <label>Manufacturing</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/repairs.png') }}" /><br/>
                    <label>Repairs</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/accounting.png') }}" /><br/>
                    <label>Accounting</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/payroll.png') }}" /><br/>
                    <label>Payroll</label>
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="{{ url('icons/projects.png') }}" /><br/>
                    <label>Projects</label>
                </a>
            </li>
        </ul>
    </div>
</div>
@endsection