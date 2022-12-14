<?php
/***********************************************************
displayjoblogs.blade.php
Product :
Version : 1.0
Release : 1
Date Created : Jun 13, 2020
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2020

Page Description :

***********************************************************/

?>

@foreach($lst_jobs  as $index => $job_info)
	<tr>
       <td>{{ $job_info->j_job_barecode }}</td>
       <td>{{ $job_info->j_due_date }}</td>  
       <td>{{ $job_info->j_job_title }}</td>
       <td>{{ $job_info->Status->js_status_title }}</td>
    </tr>
@endforeach