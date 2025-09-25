<?php
/***********************************************************
 * PurchaseRequisitions.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/4/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/


class PurchaseRequisitions extends Model
{
    protected $table = 'purchase_requisitions';
    public $timestamps = false;
    protected $primaryKey = "pr_id";

    public function Company()
    {
        return $this->hasOne('App\models\System\Companies', 'cc_id','pr_company_id');
    }

    public function Requester()
    {
        return $this->hasOne('App\models\Users\Users', 'id','pr_requester_id');
    }


    public function Department()
    {
        return $this->hasOne('App\models\System\Departments', 'sd_id','pr_department_id');
    }


    public function CostCenter()
    {
        return $this->hasOne('App\models\CostCenter\CostCenters', 'ac_id','pr_cost_center_id');
    }

    public function Project()
    {
        return $this->hasOne('App\models\PMP\Project', 'pp_id','pr_project_id');
    }

}
