<?php
/***********************************************************
 * ApprovalPoliciesSteps.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 7/4/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

namespace App\models\Purchasing;
use Model;

class PurchaseApprovalWorkflowSteps extends Model
{
    protected $table = 'purchase_pr_approval_workflow_steps';
    public $timestamps = false;
    protected $primaryKey = "ws_id";

}
