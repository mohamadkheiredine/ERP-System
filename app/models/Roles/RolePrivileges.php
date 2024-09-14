<?php
/***********************************************************
RolePrivileges.php
Product :
Version : 1.0
Release : 2
Date Created : Jan 3, 2016
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2015

Page Description :
{Enter page description Here}
***********************************************************/




namespace App\models\Roles;

use Illuminate\Database\Eloquent\Model;
use Middleware;
use DB;
use Session;

class RolePrivileges extends Model
{
    protected $table        = 'role_privileges';
    public $timestamps      = false;
    protected $dateFormat   = 'Y-m-d H:i:s';
    protected $primaryKey   = 'rp_id';
   
}