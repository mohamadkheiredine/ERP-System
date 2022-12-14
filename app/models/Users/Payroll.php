<?php
/***********************************************************
Payroll.php
Product :
Version : 1.0
Release : 1
Date Created : Nov 15, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/


namespace App\models\Users;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{ 
    protected   $table          = 'usr_payroll';
    public      $timestamps     = false;
    protected   $primaryKey     = "up_id";
    
    
    public function users()
    {
        return $this->hasOne('App\models\Users\Users','id','fk_user_id');
    }
    
    
    public function currencies()
    {
        return $this->hasOne('App\models\System\Currency','cc_id','up_currency_id');
    }
}
