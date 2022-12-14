<?php
/***********************************************************
ProjectStatus.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 26, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/


namespace App\models\PMP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    protected   $table          = 'pm_project_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "ps_id";
}