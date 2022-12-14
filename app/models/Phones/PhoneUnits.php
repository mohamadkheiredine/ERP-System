<?php
/***********************************************************
PhoneUnits.php
Product :
Version : 1.0
Release : 1
Date Created : Jul 31, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/



namespace App\models\Phones;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class PhoneUnits extends Model
{
    protected   $table          = 'ph_phone_units';
    public      $timestamps     = false;
    protected   $primaryKey     = "pu_id";
    
    public function Currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','pu_currency_id');
    }
    
}