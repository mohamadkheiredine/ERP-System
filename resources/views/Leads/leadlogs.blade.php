<?php
/***********************************************************
leadlogs.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Aug 20, 2019
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2019

Page Description :

***********************************************************/

?>

<div class="m_datatable" id="LstLeadLogs">
<table class="m-datatable" id="LogsDatatables" width="100%">
    		<thead>
    			<tr> 
    				<th title="Id"> ID </th>
    				<th title="Log Type"> Log Type </th>
    				<th title="Log Description"> Log Description </th>
    				<th title="Log Date"> Log Date </th> 
    			</tr>
    		</thead>
    		<tbody>
    			  	@foreach($CRMLogs as $index => $log_info)
                    <tr  class="odd gradeX"> 
                       <td>{{ $log_info->cl_id }}</td>
                       <td>{{ $log_info->ll_log_type }}</td>
                       <td>{{ $log_info->ll_log_description }}</td>
                       <td>{{ $log_info->ll_log_date }}</td>
                    </tr>
                    @endforeach
    		</tbody>
    </table>
</div>