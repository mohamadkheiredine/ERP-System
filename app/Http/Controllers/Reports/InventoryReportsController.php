<?php
/***********************************************************
CRMReportsController.php
Product :
Version : 1.0
Release : 1
Date Created : Sep 6, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
List Reports for CRM Module
***********************************************************/

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\models\Inventory\WareHouses;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use League\Csv\Writer;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use Config;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\models\Users\Users;
use App\models\System\Industry;
use App\models\CRM\CRMLeadSources;
use App\models\System\Countries;
use App\models\CRM\CRMClientCategories;
use App\models\CRM\CRMLeadStatus;
use App\models\CRM\CRMLeads;
use App\models\CRM\CRMAccountTypes;
use App\models\CRM\CRMAccounts;


class InventoryReportsController extends Controller
{
    public function StockStatusReport(Request $request)
    {

        $lst_warehouses = WareHouses::whereWIsDeleted(0)->get();

        $data = array(
            'lst_warehouses' => $lst_warehouses
        );

        return Response()->view('warehouses.stockstatuses',$data);
    }


    public function DisplayListStockStatus(Request $request)
    {
        $sm_stock_warehouse = $request->input("sm_stock_warehouse");
        $where_cond = "";
        if($sm_stock_warehouse > 0)
        {
            $where_cond = " AND s.fk_warehouse_id = " . $sm_stock_warehouse;
        }
        $query = "SELECT w.w_id, w.w_warehouse_name AS warehouse_name, p.p_id, p.p_product_ref, p.p_product_name, p.p_product_stock_alert AS alert_limit, COALESCE(SUM(s.is_quanity), 0) AS total_stock, CASE WHEN COALESCE(SUM(s.is_quanity), 0) = 0 THEN '❌ No Stock Available' WHEN COALESCE(SUM(s.is_quanity), 0) <= p.p_product_stock_alert THEN '⚠️ Low Stock' ELSE '✅ Sufficient' END AS stock_status FROM inventory_warehouses AS w LEFT JOIN inventory_stocks AS s ON s.fk_warehouse_id = w.w_id AND s.is_is_deleted = 0 LEFT JOIN inventory_products AS p ON p.p_id = s.fk_product_id AND p.p_product_is_deleted = 0 " . $where_cond . " GROUP BY w.w_id, w.w_warehouse_name, p.p_id, p.p_product_ref, p.p_product_name, p.p_product_stock_alert ORDER BY w.w_warehouse_name ASC, stock_status DESC, total_stock ASC;";
        $lst_stock_status = DB::select($query);

        $result_array = array();

        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Completed Successfully";

        $data = array(
            "lst_stock_status" => $lst_stock_status
        );
        $result_array['display'] = view("warehouses.lststockstatuses",$data)->render();


        return Response()->json($result_array);
    }

    public function DownloadStockStatusReport(Request $request)
    {
        $type = $request->input("type");
        $sm_stock_warehouse = $request->input("sm_stock_warehouse");
        $where_cond = "";
        if($sm_stock_warehouse > 0)
        {
            $where_cond = " AND s.fk_warehouse_id = " . $sm_stock_warehouse;
        }
        $query = "SELECT w.w_id, w.w_warehouse_name AS warehouse_name, p.p_id, p.p_product_ref, p.p_product_name, p.p_product_stock_alert AS alert_limit, COALESCE(SUM(s.is_quanity), 0) AS total_stock, CASE WHEN COALESCE(SUM(s.is_quanity), 0) = 0 THEN '❌ No Stock Available' WHEN COALESCE(SUM(s.is_quanity), 0) <= p.p_product_stock_alert THEN '⚠️ Low Stock' ELSE '✅ Sufficient' END AS stock_status FROM inventory_warehouses AS w LEFT JOIN inventory_stocks AS s ON s.fk_warehouse_id = w.w_id AND s.is_is_deleted = 0 LEFT JOIN inventory_products AS p ON p.p_id = s.fk_product_id AND p.p_product_is_deleted = 0 " . $where_cond . " GROUP BY w.w_id, w.w_warehouse_name, p.p_id, p.p_product_ref, p.p_product_name, p.p_product_stock_alert ORDER BY w.w_warehouse_name ASC, stock_status DESC, total_stock ASC;";
        $lst_stock_status = DB::select($query);
        if($type == 'csv')
        {
            $data = array();
            $data[] = ['Warehouse','Product Code','Product Name','Total Stock','Stock Status'];

            foreach ($lst_stock_status as $index => $status_info)
            {
                $data[] = [$status_info->warehouse_name, $status_info->p_product_ref, $status_info->p_product_name,$status_info->total_stock,$status_info->stock_status];
            }


            $csv = Writer::createFromFileObject(new \SplTempFileObject());

            $csv->insertAll($data);

            return $csv->output('data.csv');
        }
        else
        {

            $data = array(
                "lst_stock_status" => $lst_stock_status
            );
            $display = view('warehouses.printstockstatus',$data)->render();

            return PDF::loadHTML($display)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->download('printstockstatus-report.pdf');
        }



    }
}
