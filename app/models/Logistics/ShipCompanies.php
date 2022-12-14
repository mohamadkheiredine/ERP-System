<?php
/***********************************************************
ShipCompanies.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 8, 2019
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

class ShipCompanies extends Model
{
    protected   $table          = 'lg_shipment_companies';
    public      $timestamps     = false;
    protected   $primaryKey     = "sc_id";
}