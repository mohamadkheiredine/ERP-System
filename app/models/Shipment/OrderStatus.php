<?php
/***********************************************************
OrderStatus.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Shipment;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected   $table          = 'sh_order_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "ss_id";
}