<?php
/***********************************************************
ExportController.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 4, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :
Controler where the system export data from database to a file
***********************************************************/


namespace App\Http\Controllers\Utilities;

use App\User;
use Validator;
use Input;
use Request;
use Session;
use Config;
use Redirect;
use File;
use DB;
use App\models\System\Appconfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\models\CRM\CRMLeads;
use App\models\Timesheet\TimesheetRecords;
use App\Library\TimesheetManager;
use App\models\Users\Users;


class ExportController extends Controller
{
    
    /**
     * Export Data to CSV 
     * 
     * @author Moe Mantach
     * @access public
     * @param Request $request
     */
    public function ExporttoCsv($data_type , $params)
    {
        switch ($data_type)
        {
            case "leads":
                {
                    $lead_ids       = $params;
                    $lead_ids_array = explode(",", $lead_ids);
                    
                    $lst_lead = CRMLeads::whereIn("cl_id",$lead_ids_array)->get();
                    
                    $file = 'leads-' . date("Y-M-D") . "-" . time() . '.xls';
                    ob_start();
                    
                    echo "<table border='1'> ";
                    echo "<tr>";
                    echo "<th>First Name</th>";
                    echo "<th>Last Name</th>";
                    echo "<th>Company Name</th>";
                    echo "<th>Email</th>";
                    echo "<th>Phone</th>";
                    echo "<th>Mobile</th>";
                    echo "<th>Fax</th>";
                    echo "</tr>";
                    foreach ($lst_lead as $key => $lead_info )
                    {
                        echo "<tr>";
                        echo "<td>" . $lead_info->cl_first_name. "</td>";
                        echo "<td>" . $lead_info->cl_last_name. "</td>";
                        echo "<td>" . $lead_info->cl_company_name . "</td>";
                        echo "<td>" . $lead_info->cl_email. "</td>";
                        echo "<td>" . $lead_info->cl_phone. "</td>";
                        echo "<td>" . $lead_info->cl_mobile. "</td>";
                        echo "<td>" . $lead_info->cl_fax. "</td>";
                        echo "</tr>";
                    }
                    echo "</table> ";
                    
                    $content = ob_get_contents();
                    ob_end_clean();
                    header("Expires: 0");
                    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                    header("Cache-Control: no-store, no-cache, must-revalidate");
                    header("Cache-Control: post-check=0, pre-check=0", false);
                    header("Pragma: no-cache");
                    header("Content-type: application/vnd.ms-excel;charset:UTF-8");
                    header('Content-length: ' . strlen($content));
                    header('Content-disposition: attachment; filename=' . basename($file));
                    echo $content;
                    return;
                    
                }
            break;
            case "transportationemployees" :
                {
                    $date_array     = explode("-", $params);
                    
                    $year   = $date_array[0];
                    $month  = $date_array[1];
                    
                    $lst_timesheet_info                 = TimesheetRecords::whereYear('ts_timesheet_date',$year)->whereMonth('ts_timesheet_date',$month)->get();
                    $TimesheetManager                   = new TimesheetManager();
                    $lst_users                          = Users::whereUIsActive(1)->whereUIsDeleted(0)->get();
                    $users_array                        = CreateDatabaseArrayByIndex($lst_users, "id");
                    $timesheet_array                    = $TimesheetManager->GetTimesheetArray($lst_timesheet_info);
                    $params_array = array(
                        "lst_timesheet_info" => $lst_timesheet_info,
                        "users_array" => $users_array,
                    );
                    $transportation_employees_array     = $TimesheetManager->GetTransportationEmployeeArray($params_array);
                    $file = 'transemp-' . date("Y-M-D") . "-" . time() . '.xls';
                    ob_start();
                    ?>
                    <table border="1">
                    	<thead>
                    		<tr>
                    			<th>
                    				#
                    			</th>
                    			<th>
                    				Full Name
                    			</th>
                    			<th>
                    				Transportation Days
                    			</th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    		<?php 
                    		foreach( $users_array as $index => $user_info ){
                    		      ?>
                    		      	<tr>
                    			<th scope="row">
                    				<?php echo $user_info->id; ?>
                    			</th>
                    			<td>
                    				<?php echo $user_info->u_fullname; ?>
                    			</td>
                    			<td>
                    				<?php echo ((isset($transportation_employees_array[ $user_info->id ]) ) ? $transportation_employees_array[ $user_info->id ] * session('company_transportation_fees') : 0 ); ?>&nbsp;&nbsp;<b><?php echo session('currency_symbol')?></b>
                    			</td> 
                    		</tr>
                    		      <?php 
                    		  }
                    		?>
                    	</tbody>
                    </table>
                    <?php 
                    $content = ob_get_contents();
                    ob_end_clean();
                    header("Expires: 0");
                    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                    header("Cache-Control: no-store, no-cache, must-revalidate");
                    header("Cache-Control: post-check=0, pre-check=0", false);
                    header("Pragma: no-cache");
                    header("Content-type: application/vnd.ms-excel;charset:UTF-8");
                    header('Content-length: ' . strlen($content));
                    header('Content-disposition: attachment; filename=' . basename($file));
                    echo $content;
                    return;
                }
            break;
        }
    }
    
}