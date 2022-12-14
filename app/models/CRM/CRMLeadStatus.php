<?php
/***********************************************************
CRMLeadStatus.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\CRM;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CRMLeadStatus extends Model
{ 
    protected   $table          = 'crm_lead_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "ls_id";
}