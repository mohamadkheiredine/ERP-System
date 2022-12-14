<?php
/***********************************************************
ProjectTypes.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 19, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Product categories model
***********************************************************/

namespace App\models\PMP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ProjectTypes extends Model
{
    protected   $table          = 'pm_project_types';
    public      $timestamps     = false;
    protected   $primaryKey     = "pt_id";
}