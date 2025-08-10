<?php
/***********************************************************
InboundCall.php
Product : titanerp
Version : 1.0
Release : 1
Date Created : 17-08-2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\CallCenter;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class InboundCallProducts extends Model
{
    protected   $table          = 'callcenter_calls_products';
    public      $timestamps     = false;
    protected   $primaryKey     = "cp_id";

    public function Technician()
    {
        return $this->hasOne('App\models\Users\Users', 'id','cp_technician_id');
    }

    public function Call()
    {
        return $this->hasOne('App\models\CallCenter\InboundCall', 'ic_id','fk_call_id');
    }


    public function Product()
    {
        return $this->hasOne('App\models\Inventory\Products', 'p_id','cp_product_id');
    }




}
