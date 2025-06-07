<?php
/***********************************************************
 * aptsummary.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 6/1/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/
?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Summary</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 40px;
            background: #fdfdfd;
            color: #333;
        }
        .paper {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            color: #007bff;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            color: #555;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .col {
            width: 50%;
            margin-bottom: 10px;
        }
        .label {
            font-weight: 600;
        }
        .value {
            margin-left: 5px;
        }
        .textarea-box {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
            min-height: 80px;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
<div class="paper">
    <div class="header">
        <h2>Appointment Summary</h2>
        <p>Date: <strong>%date_apt%</strong> &nbsp; | &nbsp; Time: <strong>%apt_time%</strong></p>
    </div>

    <div class="section">
        <div class="section-title">Lead & Sales Info</div>
        <div class="row">
            <div class="col"><span class="label">Lead:</span> <span class="value">%lead_name%</span></div>
            <div class="col"><span class="label">Salesman:</span> <span class="value">%sales_name%</span></div>
            <div class="col"><span class="label">Telemarketing:</span> <span class="value">%telemarketing_name%</span></div>
            <div class="col"><span class="label">Lead Type:</span> <span class="value">%lead_type%</span></div>
            <div class="col"><span class="label">Appointment Result:</span> <span class="value">%app_result%</span></div>
            <div class="col"><span class="label">Confirmed:</span> <span class="value">%is_confirmed%</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Client Details</div>
        <div class="row">
            <div class="col"><span class="label">Phone:</span> <span class="value">%client_phone%<</span></div>
            <div class="col"><span class="label">Area:</span> <span class="value">%client_area%</span></div>
            <div class="col"><span class="label">With:</span> <span class="value">%client_with%</span></div>
            <div class="col"><span class="label">Address:</span> <span class="value">%client_address%</span></div>
            <div class="col"><span class="label">Referred By:</span> <span class="value">%client_referredby%</span></div>
            <div class="col"><span class="label">No. of Leads:</span> <span class="value">%number_of_leads%</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Notes</div>
        <div class="textarea-box">
            %app_notes%
        </div>
    </div>

    <div class="section">
        <div class="section-title">Details</div>
        <div class="textarea-box">
            %app_details%
        </div>
    </div>
</div>
</body>
</html>
