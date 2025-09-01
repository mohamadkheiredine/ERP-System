<?php
/***********************************************************
 * TaskDependencies.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 8/26/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/



namespace App\models\PMP;

use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class TaskDependencies extends Model
{
    protected   $table          = 'pm_task_dependencies';
    public      $timestamps     = false;
    protected   $primaryKey     = "td_dep_id";

    public function Task()
    {
        return $this->hasOne('App\models\PMP\ProjectTasks', 'wt_id','td_task_id');
    }

}
