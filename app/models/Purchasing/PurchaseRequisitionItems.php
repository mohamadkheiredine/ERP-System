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



namespace App\models\Purchasing;
use Model;

class PurchaseRequisitionItems extends Model
{
    protected $table = 'purchase_requisition_items';
    public $timestamps = false;
    protected $primaryKey = "ri_id";

}
