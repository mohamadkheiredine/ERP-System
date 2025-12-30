<?php
/***********************************************************
 * listcumulativeleadsreport.blade.php
 * Product :titanerp
 * Version : 1.0
 * Release : 1
 * Date Created : 12/28/2025
 * Developed By  : Mohamad Mantach   PHP Department itm Solutions
 * All Rights Reserved ,   itm Solutions COPYRIGHT 2025
 *
 * Page Description :
 ***********************************************************/

?>

@foreach($cumulative_results as $index => $record_info)
    <tr>
        <td title="Date"> {{ $record_info->lead_date }} </td>
        <td title="Daily Count"> {{ $record_info->daily_leads }} </td>
        <td title="Cumulative Number of Leads"> {{ $record_info->cumulative_total }}  </td>
    </tr>
@endforeach
