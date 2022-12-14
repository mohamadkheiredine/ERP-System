<?php
/***********************************************************
TaskStatus.php
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

class TaskStatus extends Model
{
    protected   $table          = 'pm_task_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "ts_id";
}