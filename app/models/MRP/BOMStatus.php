<?php
/***********************************************************
BOMStatus.php
Product : titan HMIS
Version : 1.0
Release : 2
Date Created Sep 12, 2023
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2023

Page Description :
{Enter page description Here}
***********************************************************/


namespace App\models\MRP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class BOMStatus extends Model
{ 
    protected   $table          = 'mrp_bom_status';
    public      $timestamps     = false;
    protected   $primaryKey     = "mb_id";
    
 
}