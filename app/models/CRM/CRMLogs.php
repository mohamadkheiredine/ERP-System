<?php
/***********************************************************
CRMLeadLogs.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 13, 2019
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

class CRMLogs extends Model
{ 
    protected   $table          = 'crm_logs';
    public      $timestamps     = false;
    protected   $primaryKey     = "cl_id";
}