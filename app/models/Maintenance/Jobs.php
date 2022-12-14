<?php
/***********************************************************
Jobs.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 8, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/



namespace App\models\Maintenance;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected   $table          = 'main_jobs';
    public      $timestamps     = false;
    protected   $primaryKey     = "j_id"; 
    
    public function Users()
    {
        return $this->hasOne('App\Models\Users\Users', 'id','j_user_id');
    }
    
    public function Currency()
    {
        return $this->hasOne('App\Models\System\Currency', 'cc_id','j_currency_id');
    }
    
    public function Status()
    {
        return $this->hasOne('App\Models\Maintenance\JobStatus', 'js_id','j_job_status_id');
    }
    
    public function Customer()
    {
        return $this->hasOne('App\Models\Inventory\Customers', 'ic_id','j_customer_id');
    }
    
}