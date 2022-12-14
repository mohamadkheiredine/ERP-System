<?php
/***********************************************************
IndexController.php
Product :
Version : 1.0
Release : 2
Date Created :Aug 19, 2018
Developed By  : Mohamad Mantach   PHP Department Softweb S.A.R.L
All Rights Reserved ,    Softweb S.A.R.L COPYRIGHT 2018

Page Description :
{Enter page description Here}
***********************************************************/

namespace App\Http\Controllers;

use App\User;
use Validator;
use Input;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use Redirect;
use DB;
use App\Http\Controllers\Controller;
use App\Library\EncryptionManager;
use Illuminate\Support\Facades\Hash;
use App\models\System\Companies;

class IndexController extends Controller
{

    public function index(Request $request)
    {
        
        $path_filename= public_path() . '/.license';
       
        if(filesize($path_filename) == 0)
            return redirect::to('generatelicense');
        
        $company_info = Companies::whereCdIsDeleted(0)->whereCdPrimaryCompany(1)->get();
        
        $data = array(
            'company_info' => $company_info
        );
        
        if ($request->session()->has('user_id')) {
            return Redirect::to('/dashboard');
        }
        
        return Response()->view("users.login",$data);
    }
    
    
    /**
     * generate license info in the license file and change 
     * module global variables
     * 
     * @author Moe Mantach
     * @access public
     */
    public function GenerateLicense()
    {
        $path_filename= public_path() . '/.license';
        
        $fp = fopen($path_filename, "ab+");
        if(filesize($path_filename) > 0)
            $license_content = fgets($fp , filesize($path_filename));
        
            
        $data = array();
        return Response()->view('installation.license',$data);
    }
    
    
    /**
     * Save Data from the page about enabled and siabled module and
     * Encrypt it and put it as a json sequence in the .license file
     * 
     * @author Moe Mantach
     * @access public
     */
    public function GenerateLicenseFile(Request $request)
    {
        $result_array = array();
        $timesheet_module           = $request->input('TIMESHEET_MODULE');
        $inventory_module           = $request->input('INVENTORY_MODULE');
        $banking_module             = $request->input('BANKING_MODULE');
        $payroll_module             = $request->input('PAYROLL_MODULE');
        $srm_module                 = $request->input('SRM_MODULE');
        $accounting_module          = $request->input('ACCOUNTING_MODULE');
        $billing_module             = $request->input('BILLING_MODULE');
        $manufacturing_module       = $request->input('MANUFACTURING_MODULE');
        $production_module          = $request->input('PRODUCTION_MODULE');
        $projects_module            = $request->input('PROJECTS_MODULE');
        $sales_module               = $request->input('SALES_MODULE');
        $crm_module                 = $request->input('CRM_MODULE');
        $shipment_module            = $request->input('SHIPMENT_MODULE');
        $logistics_module           = $request->input('LOGISTICS_MODULE');
        
        
        $license_array = array();
        
        $license_array['TIMESHEET_MODULE']  = ( $timesheet_module == null ) ? 0 : 1;
        $license_array['INVENTORY_MODULE']  = ( $inventory_module== null ) ? 0 : 1;
        $license_array['BANKING_MODULE']    = ( $banking_module== null ) ? 0 : 1;
        $license_array['PAYROLL_MODULE']    = ( $payroll_module== null ) ? 0 : 1;
        $license_array['SRM_MODULE']           = ( $srm_module == null ) ? 0 : 1;
        $license_array['ACCOUNTING_MODULE']     = ( $accounting_module== null ) ? 0 : 1;
        $license_array['BILLING_MODULE']        = ( $billing_module== null ) ? 0 : 1;
        $license_array['MANUFACTURING_MODULE']  = ( $manufacturing_module== null ) ? 0 : 1;
        $license_array['PRODUCTION_MODULE']     = ( $production_module == null ) ? 0 : 1;
        $license_array['PROJECTS_MODULE']       = ( $projects_module == null ) ? 0 : 1;
        $license_array['SALES_MODULE']          = ( $sales_module== null ) ? 0 : 1;
        $license_array['CRM_MODULE']            = ( $crm_module== null ) ? 0 : 1;
        $license_array['SHIPMENT_MODULE']       = ( $shipment_module== null ) ? 0 : 1;
        $license_array['LOGISTICS_MODULE']      = ( $logistics_module== null ) ? 0 : 1;
        
        $license_sequence = json_encode($license_array);
        
        $encrypter = new EncryptionManager();
        
        $encrypted_sequence = $encrypter->encryptionsequence($license_sequence);

        $path_filename= public_path() . '/.license';
        
        $fp = fopen($path_filename, "ab+");
        fwrite($fp, $encrypted_sequence);
        fclose($fp);
        
        $result_array['is_error'] = 0;
        $result_array['error_msg'] = "License has been Generated !!";
        
        return Response()->json($result_array);
        
    }


    public function RedirectMain()
    {
        return Redirect::to('/');
    }
}