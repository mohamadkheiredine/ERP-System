<?php
/***********************************************************
JobItems.php
Product :
Version : 1.0
Release : 1
Date Created : May 30, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

namespace App\models\Maintenance;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class JobItems extends Model
{
    protected   $table          = 'main_job_items';
    public      $timestamps     = false;
    protected   $primaryKey     = "ji_id"; 
    
    public function Currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','ji_currency_id');
    }
    
    
}