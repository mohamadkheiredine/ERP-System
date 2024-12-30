<?php

/***********************************************************
downloadclosuresalesapp
Product : titanerp
Version : 1.0
Release : 1
Date Created : Nov 10, 2024
Developed By  : Mohamad Mantach   PHP Department itm Solutions
All Rights Reserved ,   itm Solutions COPYRIGHT 2024

Page Description :
{Enter page description Here}
***********************************************************/

?>
<table style="width:100%">
        <thead>
                <tr style="background-color:#c0c0c0;padding:10px">
                        <th  style="margin:3px;border:solid 1px #000">#</th>
                        <th  style="margin:3px;border:solid 1px #000">closing average</th>
                        <?php  foreach ($aptres_array as $key => $res) { ?>
                          <th  style="margin:3px;border:solid 1px #000">{{ $res }}</th>   
                       <?php } ?>
                        
                </tr>
        </thead>
        <tbody>
             <?php  foreach ($sales_array as $sales_id => $salesman) { ?>
              <tr style="padding:10px">
               <td style="margin:3px;border:solid 1px #000">{{$salesman}}</td>
               <td style="margin:3px;border:solid 1px #000">{{isset($percentage_colsure_array[$sales_id]) ? $percentage_colsure_array[$sales_id] : "-" }}&nbsp;%</td>
                <?php  foreach ($aptres_array as $res_id => $res) { ?>
                    <td style="margin:3px;border:solid 1px #000">{{ (isset($total_results_app[ $sales_id ]) && isset($total_results_app[ $sales_id ][ $res_id ])) ? $total_results_app[ $sales_id ][ $res_id ] : "-" }}</td>   
                 <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
</table>