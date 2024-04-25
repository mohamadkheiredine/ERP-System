<?php
/************************************************************
 ConfigurationController.php
 Product :
 Version : 1.0
 Release : 0
 Date Created : Sep 3, 2015
 Developed By  : Mohamad. Mantach  PHP Department Softweb S.A.R.L
 All Rights Reserved, Softweb S.A.R.L COPYRIGHT 2015
 
 Page Description :
 --
 ************************************************************/

namespace App\Http\Controllers\Utilities;

use App\User;
use Validator;
use Input; 
use Session;
use Config;
use Redirect;
use File;
use DB;
use App\models\System\AppConfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\library\AccountingManager;



class ConfigurationController extends Controller
{
    
    /**
     * Save Configuration to the database and to the config file
     */
    public function SaveConfiguration()
    {
        $sa_id                  = $_POST['sa_id'];
        $sa_config_value        = $_POST['sa_config_value'];
        $sa_config_description  = $_POST['sa_config_description'];
        $sa_config_index        = $_POST['sa_config_index'];
        
        $str_save = "<?php \n return [ \n\n\t";
        for ($i = 0; $i < count($sa_id); $i++)
        {
            $fields_array = array(
                'sa_config_value' => $sa_config_value[$i],
                'sa_config_description' => $sa_config_description[$i]
            );
            $config_file[ $sa_config_index[$i] ] = $sa_config_value[$i];
            DB::table('sys_appconfig')->where('sa_id', $sa_id[$i])->update($fields_array);
            $str_save .= '"' . $sa_config_index[$i] . '"=>"' . $sa_config_value[$i] . '"';
            if($i < count($sa_id) - 1)
            {
                $str_save .= ',' . "\n";
            }
        }
        $str_save .= "]; \n?>";
        
        $dir_config_path = config_path() . "\appconfig.php";
        $fp = fopen($dir_config_path,"w");
        fwrite($fp,$str_save);
        fclose($fp);
        
        $result_array = array();
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "Operation Complete Succesfully";
        
        return $result_array;
        
    }
    
    /**
     * Generate Voucher Code For invoices/Receipts/Vouchers Based on type
     * and date Sent to the function 
     * @param Request $request
     */
    public function GenerateVoucherCode(Request $request)
    {
        $type = $request->input('type');
        
        switch($type)
        {
            case "invoices":
                {
                    $selected_date = $request->input("selected_date");
                    $fyear= date("Y",strtotime($selected_date));
                    $params_array = array();
                    $params_array['fyear'] = $fyear;
                    $AccountingManager = new AccountingManager();
                    
                    $invoice_code = $AccountingManager->GenerateInvoiceCode($params_array);
                    
                    $result_array = array();
                    $result_array['code'] = $invoice_code;
                    $result_array['is_error'] = 0;
                    $result_array['error_msg'] = "Generated A Invoice Code";
                    
                    return Response()->json($result_array);
                }
            break;
            case "receipts":
                {
                    $selected_date = $request->input("selected_date");
                    $fyear= date("Y",strtotime($selected_date)); 
                    $AccountingManager = new AccountingManager();
                    
                    $receipt_code = $AccountingManager->GenerateReceiptCode(0,$fyear);
                    
                    $result_array = array();
                    $result_array['code'] = $receipt_code;
                    $result_array['is_error'] = 0;
                    $result_array['error_msg'] = "Generated A Receipt Code";
                    
                    return Response()->json($result_array);
                }
            break;
            case "vouchers":
                {
                    $selected_date = $request->input("selected_date");
                    $fyear= date("Y",strtotime($selected_date));
                    $AccountingManager = new AccountingManager();
                    
                    $voucher_code = $AccountingManager->GetVoucherCode($fyear);
                    
                    $result_array = array();
                    $result_array['code']       = $voucher_code;
                    $result_array['is_error']   = 0;
                    $result_array['error_msg']  = "Generated A Payment Voucher Code";
                    
                    return Response()->json($result_array);
                }
                break;
        }
        
    }
    
    
    public function index()
    {
        $sys_configurations = AppConfig::all();
        $data = array(
            "sys_configurations" => $sys_configurations
        );
        return view('utilities.listconfigurations',$data);
    }
}