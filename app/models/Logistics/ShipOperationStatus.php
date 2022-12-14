<?php
/***********************************************************
ShipOperationStatus.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 16, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\Logistics;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ShipOperationStatus extends Model
{
    protected   $table          = 'lg_operation_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "os_id";
}