<?php
/***********************************************************
BillOfMaterials.php
Product :
Version : 1.0
Release : 1
Date Created : Dec 27, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

namespace App\models\MRP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterials extends Model
{
    //use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
    protected   $table          = 'mrp_bill_material';
    public      $timestamps     = false;
    protected   $primaryKey     = "bm_id";
    
    
    
}